<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    private const LAMA_PINJAM_HARI = 7;
    private const DENDA_PER_HARI = 2000;

    private const STATUS_MENUNGGU = ['pending', 'menunggu', 'diajukan'];
    private const STATUS_AKTIF = ['aktif', 'dipinjam'];

    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Peminjaman::with(['user', 'petugas', 'detail.buku']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        if ($request->tampil === 'belum') {
            $query->whereIn('status', self::STATUS_AKTIF);
        }

        $peminjaman = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

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
                'status' => 'Menunggu',
            ]);

            $peminjaman->detail()->create([
                'buku_id' => $buku->id,
                'jumlah' => 1,
            ]);
        });

        return redirect()->route('anggota.peminjaman.index')->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
    }

    public function verifikasi(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $request->validate([
            'keputusan' => ['required', 'in:setuju,tolak'],
        ]);

        if (! in_array(strtolower($peminjaman->status), self::STATUS_MENUNGGU, true)) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        if ($request->keputusan === 'tolak') {
            $peminjaman->update([
                'status' => 'Ditolak',
            ]);

            return back()->with('success', 'Pengajuan peminjaman ditolak.');
        }

        DB::transaction(function () use ($peminjaman) {
            foreach ($peminjaman->detail as $detail) {
                $detail->buku()->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update([
                'status' => 'Dipinjam',
                'tanggal_pinjam' => today(),
                'tanggal_jatuh_tempo' => today()->addDays(self::LAMA_PINJAM_HARI),
            ]);
        });

        return back()->with('success', 'Peminjaman disetujui & stok buku diperbarui.');
    }

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
                'tanggal_kembali' => today(),
            ]);
        });

        return back()->with('success', 'Buku berhasil ditandai dikembalikan.');
    }
}