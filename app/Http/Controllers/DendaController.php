<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DendaController extends Controller
{
    /** Staf melihat semua denda; anggota hanya denda miliknya. */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $base = Denda::query();
        if ($user->isAnggota()) {
            $base->whereHas('peminjaman', fn ($q) => $q->where('user_id', $user->id));
        }

        $totalBelumLunas = (clone $base)->where('status_bayar', Denda::BELUM_LUNAS)->sum('jumlah_denda');
        $totalLunas = (clone $base)->where('status_bayar', Denda::LUNAS)->sum('jumlah_denda');
        $jumlahBelumLunas = (clone $base)->where('status_bayar', Denda::BELUM_LUNAS)->count();

        $denda = (clone $base)
            ->with(['peminjaman.user.jenisUser', 'peminjaman.detail.buku'])
            ->when(in_array($request->status, [Denda::BELUM_LUNAS, Denda::LUNAS], true), fn ($q) => $q->where('status_bayar', $request->status))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Denda berjalan: buku masih dipinjam tapi sudah lewat jatuh tempo (belum tercatat di tabel denda).
        $berjalan = Peminjaman::with(['user.jenisUser', 'detail.buku'])
            ->where('status', Peminjaman::DIPINJAM)
            ->whereDate('tanggal_jatuh_tempo', '<', today())
            ->when($user->isAnggota(), fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('tanggal_jatuh_tempo')
            ->get()
            ->map(function (Peminjaman $p) {
                $p->hari_telat = (int) $p->tanggal_jatuh_tempo->diffInDays(today(), true);
                $p->tarif = (int) ($p->user->jenisUser->denda_per_hari ?? 0);
                $p->estimasi = $p->hari_telat * $p->tarif;

                return $p;
            });

        return view('denda.index', [
            'berjalan' => $berjalan,
            'totalBerjalan' => (int) $berjalan->sum('estimasi'),
            'denda' => $denda,
            'totalBelumLunas' => (int) $totalBelumLunas,
            'totalLunas' => (int) $totalLunas,
            'jumlahBelumLunas' => $jumlahBelumLunas,
            // Daftar transaksi untuk form "catat denda rusak/hilang" (khusus staf).
            'transaksi' => $user->isStaff()
                ? Peminjaman::with(['user', 'detail.buku'])
                    ->whereIn('status', [Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN])
                    ->latest('id')->take(100)->get()
                : collect(),
        ]);
    }

    /** Catat denda manual (buku rusak / hilang). Denda terlambat dicatat otomatis saat pengembalian. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'peminjaman_id' => ['required', 'exists:peminjaman,id'],
            'jenis' => ['required', 'in:'.Denda::RUSAK.','.Denda::HILANG],
            'jumlah_denda' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'peminjaman_id.required' => 'Pilih transaksi peminjaman.',
            'jumlah_denda.required' => 'Nominal denda wajib diisi.',
            'jumlah_denda.min' => 'Nominal denda minimal Rp1.000.',
        ]);

        Denda::buat(Peminjaman::findOrFail($data['peminjaman_id']), [
            'jenis' => $data['jenis'],
            'hari_terlambat' => 0,
            'tarif_per_hari' => 0,
            'jumlah_denda' => $data['jumlah_denda'],
            'status_bayar' => Denda::BELUM_LUNAS,
            'keterangan' => $data['keterangan'] ?? ($data['jenis'] === Denda::RUSAK ? 'Buku rusak' : 'Buku hilang'),
        ]);

        return back()->with('success', 'Denda berhasil dicatat.');
    }

    public function bayar(Denda $denda): RedirectResponse
    {
        if ($denda->status_bayar === Denda::LUNAS) {
            return back()->with('error', 'Denda ini sudah lunas.');
        }

        $denda->update([
            'status_bayar' => Denda::LUNAS,
            'tanggal_bayar' => today(),
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}
