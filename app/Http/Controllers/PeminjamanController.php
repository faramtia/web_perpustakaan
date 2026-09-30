<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    private const LAMA_PINJAM_HARI = 7;
    private const DENDA_PER_HARI = 2000;

    /**
     * Admin/petugas lihat semua transaksi.
     * Mahasiswa/dosen cuma lihat transaksi miliknya sendiri.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Peminjaman::with(['user', 'petugas', 'detail.buku']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        $peminjaman = $query->latest()->paginate(10);

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
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);

            $peminjaman->detail()->create([
                'buku_id' => $buku->id,
                'jumlah' => 1,
            ]);
        });

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
    }

    /**
     * Petugas/admin verifikasi pengajuan: setuju atau tolak.
     */
    public function verifikasi(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:setuju,tolak'],
        ]);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($request->keputusan === 'tolak') {
            $peminjaman->update([
                'status' => 'ditolak',
                'petugas_id' => Auth::id(),
            ]);

            return back()->with('success', 'Pengajuan peminjaman ditolak.');
        }

        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update([
                'status' => 'aktif',
                'petugas_id' => Auth::id(),
                'tanggal_pinjam' => today(),
                'tanggal_jatuh_tempo' => today()->addDays(self::LAMA_PINJAM_HARI),
            ]);
        });

        return back()->with('success', 'Peminjaman disetujui & stok buku diperbarui.');
    }

    /**
     * Petugas/admin proses pengembalian buku, hitung denda otomatis kalau telat.
     */
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'aktif') {
            return back()->with('error', 'Transaksi ini tidak sedang aktif dipinjam.');
        }

        DB::transaction(function () use ($peminjaman) {
            $jatuhTempo = $peminjaman->tanggal_jatuh_tempo;
            $hariTerlambat = today()->gt($jatuhTempo) ? today()->diffInDays($jatuhTempo) : 0;
            $totalDenda = $hariTerlambat * self::DENDA_PER_HARI;

            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->increment('stok', $detail->jumlah);
                $detail->update([
                    'status_kembali' => 'sudah',
                    'denda' => $totalDenda,
                ]);
            }

            $peminjaman->update([
                'status' => $hariTerlambat > 0 ? 'terlambat' : 'dikembalikan',
                'tanggal_kembali' => today(),
            ]);
        });

        return back()->with('success', 'Buku berhasil ditandai dikembalikan.');
    }
}
