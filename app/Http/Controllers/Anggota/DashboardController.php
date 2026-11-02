<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Event;
use App\Models\EventPeserta;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->loadMissing('jenisUser');

        $terdaftar = EventPeserta::where('user_id', $user->id)->pluck('events_id')->all();

        return view('dashboard.anggota', [
            'peminjamanAktif' => $user->peminjaman()
                ->whereIn('status', [Peminjaman::MENUNGGU, Peminjaman::DIPINJAM])
                ->with('detail.buku')
                ->orderBy('tanggal_jatuh_tempo')
                ->get(),
            'totalDenda' => (int) Denda::whereHas('peminjaman', fn ($q) => $q->where('user_id', $user->id))
                ->where('status_bayar', Denda::BELUM_LUNAS)
                ->sum('jumlah_denda'),
            'reservasiSaya' => $user->reservasi()->with('buku')->latest('id')->take(5)->get(),
            'tugasAkhirSaya' => $user->tugasAkhir()->latest('id')->take(3)->get(),
            'eventMendatang' => Event::withCount('peserta')
                ->whereDate('tanggal_mulai', '>=', today())
                ->orderBy('tanggal_mulai')
                ->take(3)
                ->get(),
            'terdaftar' => $terdaftar,
            'kunjunganBulanIni' => $user->absensi()
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
        ]);
    }
}
