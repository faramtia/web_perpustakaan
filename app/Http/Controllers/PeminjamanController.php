<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /** Maksimal judul buku dalam satu pengajuan. */
    private const MAKS_BUKU = 3;

    /**
     * Admin/petugas lihat semua transaksi.
     * Mahasiswa/dosen cuma lihat transaksi miliknya sendiri.
     *
     * Filter: ?tampil=belum (hanya yang masih dipinjam / pengembalian) dan ?status=...
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Peminjaman::with(['user.jenisUser', 'petugas', 'detail.buku', 'denda']);

        if ($user->isAnggota()) {
            $query->where('user_id', $user->id);
        }

        if ($request->tampil === 'belum') {
            $query->where('status', Peminjaman::DIPINJAM);
        } elseif ($request->status === 'Terlambat') {
            $query->where('status', Peminjaman::DIPINJAM)->whereDate('tanggal_jatuh_tempo', '<', today());
        } elseif (in_array($request->status, [Peminjaman::MENUNGGU, Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN, Peminjaman::DITOLAK], true)) {
            $query->where('status', $request->status);
        }

        if ($user->isStaff() && $request->filled('q')) {
            $q = trim($request->q);
            $query->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$q}%")->orWhere('nim_nip', 'like', "%{$q}%"));
        }

        return view('peminjaman.index', [
            'peminjaman' => $query->latest('id')->paginate(10)->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('peminjaman.create', [
            'buku' => Buku::with(['kategori', 'lokasi'])->where('stok', '>', 0)->orderBy('judul')->get(),
            'dipilih' => (int) $request->query('buku'),
            'maksBuku' => self::MAKS_BUKU,
            'aturan' => Auth::user()->jenisUser,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'array', 'min:1', 'max:'.self::MAKS_BUKU],
            'buku_id.*' => ['required', 'distinct', 'exists:buku,id'],
        ], [
            'buku_id.required' => 'Pilih minimal satu buku.',
            'buku_id.max' => 'Maksimal '.self::MAKS_BUKU.' buku dalam satu pengajuan.',
            'buku_id.*.exists' => 'Buku yang dipilih tidak ditemukan.',
        ]);

        $buku = Buku::whereIn('id', $request->buku_id)->get();

        foreach ($buku as $b) {
            if (! $b->tersedia()) {
                return back()->withInput()->with('error', "Stok \"{$b->judul}\" sedang kosong. Silakan buat reservasi.");
            }
        }

        DB::transaction(function () use ($buku) {
            // tanggal_pinjam wajib diisi di database; diperbarui saat petugas menyetujui.
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::id(),
                'tanggal_pinjam' => today(),
                'status' => Peminjaman::MENUNGGU,
            ]);

            foreach ($buku as $b) {
                $peminjaman->detail()->create(['buku_id' => $b->id, 'jumlah' => 1]);
            }
        });

        return redirect()->route('anggota.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
    }

    public function verifikasi(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:setuju,tolak'],
        ]);

        if ($peminjaman->status !== Peminjaman::MENUNGGU) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($request->keputusan === 'tolak') {
            $peminjaman->update([
                'status' => Peminjaman::DITOLAK,
                'petugas_id' => Auth::id(),
            ]);

            return back()->with('success', 'Pengajuan peminjaman ditolak.');
        }

        $peminjaman->load('detail.buku', 'user.jenisUser');

        // Lama pinjam mengikuti aturan role peminjam (kolom jenis_user.lama_pinjam_hari).
        $lamaPinjam = (int) ($peminjaman->user->jenisUser->lama_pinjam_hari ?? 90);

        $gagal = null;

        DB::transaction(function () use ($peminjaman, $lamaPinjam, &$gagal) {
            foreach ($peminjaman->detail as $detail) {
                // Kunci baris buku supaya dua petugas tidak mengurangi stok yang sama bersamaan.
                $buku = Buku::lockForUpdate()->find($detail->buku_id);

                if (! $buku || $buku->stok < $detail->jumlah) {
                    $gagal = $buku->judul ?? 'buku';

                    return;
                }
            }

            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update([
                'status' => Peminjaman::DIPINJAM,
                'petugas_id' => Auth::id(),
                'tanggal_pinjam' => today(),
                'tanggal_jatuh_tempo' => today()->addDays($lamaPinjam),
            ]);
        });

        if ($gagal !== null) {
            return back()->with('error', "Stok \"{$gagal}\" tidak mencukupi.");
        }

        return back()->with('success', 'Peminjaman disetujui & stok buku diperbarui.');
    }

    /**
     * Petugas/admin proses pengembalian buku. Kalau terlambat, denda dicatat di tabel `denda`.
     */
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIPINJAM) {
            return back()->with('error', 'Transaksi ini tidak sedang dipinjam.');
        }

        $peminjaman->load('detail', 'user.jenisUser');

        $hariTerlambat = 0;
        if ($peminjaman->tanggal_jatuh_tempo && today()->gt($peminjaman->tanggal_jatuh_tempo)) {
            $hariTerlambat = (int) $peminjaman->tanggal_jatuh_tempo->diffInDays(today(), true);
        }

        $tarif = (int) ($peminjaman->user->jenisUser->denda_per_hari ?? 0);
        $totalDenda = $hariTerlambat * $tarif;

        DB::transaction(function () use ($peminjaman, $hariTerlambat, $tarif, $totalDenda) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->increment('stok', $detail->jumlah);
            }

            if ($hariTerlambat > 0 && $totalDenda > 0) {
                // tarif_per_hari disimpan sebagai snapshot supaya perubahan tarif nanti
                // tidak mengubah denda yang sudah tercatat.
                Denda::buat($peminjaman, [
                    'jenis' => Denda::TERLAMBAT,
                    'hari_terlambat' => $hariTerlambat,
                    'tarif_per_hari' => $tarif,
                    'jumlah_denda' => $totalDenda,
                    'status_bayar' => Denda::BELUM_LUNAS,
                    'keterangan' => "Terlambat {$hariTerlambat} hari",
                ]);
            }

            $peminjaman->update([
                'status' => Peminjaman::DIKEMBALIKAN,
                'petugas_id' => Auth::id(),
                'tanggal_kembali' => today(),
            ]);
        });

        $pesan = 'Buku berhasil ditandai dikembalikan.';
        if ($totalDenda > 0) {
            $pesan .= ' Terlambat '.$hariTerlambat.' hari, denda Rp'.number_format($totalDenda, 0, ',', '.').'.';
        }

        return back()->with('success', $pesan);
    }
}
