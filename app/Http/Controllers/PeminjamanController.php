<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
<<<<<<< HEAD
    private const LAMA_PINJAM_HARI = 7;
    private const DENDA_PER_HARI = 2000;

    private const STATUS_MENUNGGU = ['pending', 'menunggu', 'diajukan'];
    private const STATUS_AKTIF = ['aktif', 'dipinjam'];

=======
    /**
     * Admin/petugas lihat semua transaksi.
     * Mahasiswa/dosen cuma lihat transaksi miliknya sendiri.
     */
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Peminjaman::with(['user.jenisUser', 'petugas', 'detail.buku', 'denda']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

<<<<<<< HEAD
        if ($request->tampil === 'belum') {
            $query->whereIn('status', self::STATUS_AKTIF);
        }

        $peminjaman = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
=======
        $peminjaman = $query->latest('id')->paginate(10);
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create(): View
    {
        $buku = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('peminjaman.create', compact('buku'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'buku_id' => ['required', 'exists:buku,id'],
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if (! $buku->tersedia()) {
            return back()->with('error', 'Stok buku sedang kosong. Silakan buat reservasi.');
        }

        DB::transaction(function () use ($buku) {
            // tanggal_pinjam wajib diisi di database; akan diperbarui saat petugas menyetujui.
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::id(),
<<<<<<< HEAD
                'status' => 'Menunggu',
=======
                'tanggal_pinjam' => today(),
                'status' => Peminjaman::MENUNGGU,
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            ]);

            $peminjaman->detail()->create([
                'buku_id' => $buku->id,
                'jumlah' => 1,
            ]);
        });

<<<<<<< HEAD
        return redirect()->route('anggota.peminjaman.index')->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
=======
        return redirect()->route('anggota.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function verifikasi(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:setuju,tolak'],
        ]);

<<<<<<< HEAD
        if (! in_array(strtolower($peminjaman->status), self::STATUS_MENUNGGU, true)) {
=======
        if ($peminjaman->status !== Peminjaman::MENUNGGU) {
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($request->keputusan === 'tolak') {
            $peminjaman->update([
<<<<<<< HEAD
                'status' => 'Ditolak',
=======
                'status' => Peminjaman::DITOLAK,
                'petugas_id' => Auth::id(),
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            ]);

            return back()->with('success', 'Pengajuan peminjaman ditolak.');
        }

        $peminjaman->load('detail.buku', 'user.jenisUser');

        foreach ($peminjaman->detail as $detail) {
            if ($detail->buku->stok < $detail->jumlah) {
                return back()->with('error', "Stok \"{$detail->buku->judul}\" tidak mencukupi.");
            }
        }

        // Lama pinjam mengikuti aturan role peminjam (kolom jenis_user.lama_pinjam_hari).
        $lamaPinjam = (int) ($peminjaman->user->jenisUser->lama_pinjam_hari ?? 90);

        DB::transaction(function () use ($peminjaman, $lamaPinjam) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update([
<<<<<<< HEAD
                'status' => 'Dipinjam',
=======
                'status' => Peminjaman::DIPINJAM,
                'petugas_id' => Auth::id(),
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
                'tanggal_pinjam' => today(),
                'tanggal_jatuh_tempo' => today()->addDays($lamaPinjam),
            ]);
        });

        return back()->with('success', 'Peminjaman disetujui & stok buku diperbarui.');
    }

<<<<<<< HEAD
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        if (! in_array(strtolower($peminjaman->status), self::STATUS_AKTIF, true)) {
            return back()->with('error', 'Transaksi ini tidak sedang aktif dipinjam.');
        }

        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->increment('stok', $detail->jumlah);
            }

            $peminjaman->update([
                'status' => 'Dikembalikan',
=======
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
            $hariTerlambat = (int) $peminjaman->tanggal_jatuh_tempo->diffInDays(today());
        }

        $tarif = (int) ($peminjaman->user->jenisUser->denda_per_hari ?? 0);
        $totalDenda = $hariTerlambat * $tarif;

        DB::transaction(function () use ($peminjaman, $hariTerlambat, $tarif, $totalDenda) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->increment('stok', $detail->jumlah);
            }

            if ($hariTerlambat > 0) {
                // tarif_per_hari disimpan sebagai snapshot supaya perubahan tarif nanti
                // tidak mengubah denda yang sudah tercatat.
                Denda::create([
                    'peminjaman_id' => $peminjaman->id,
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
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
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