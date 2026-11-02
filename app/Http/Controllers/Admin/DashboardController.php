<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\Peminjaman;
use App\Models\Reservasi;
use App\Models\TugasAkhir;
use App\Models\User;

/**
 * Dashboard staf. Dipakai bersama oleh admin dan petugas (Petugas\DashboardController
 * memanggil kelas ini) supaya tampilan dan angka kedua role selalu sama.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Grafik peminjaman: Senin sampai Minggu pada minggu ini.
        $awal = now()->startOfWeek();
        $grafik = [];
        foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $i => $hari) {
            $grafik[] = [
                'hari' => $hari,
                'jumlah' => Peminjaman::whereDate('tanggal_pinjam', $awal->copy()->addDays($i))
                    ->where('status', '!=', Peminjaman::DITOLAK)
                    ->count(),
            ];
        }

        $stokTersedia = (int) Buku::sum('stok');
        $sedangDipinjam = (int) DetailPeminjaman::whereHas('peminjaman', fn ($q) => $q->where('status', Peminjaman::DIPINJAM))->sum('jumlah');

        return view('dashboard.staff', [
            'totalBuku' => $stokTersedia,
            'judulBuku' => Buku::count(),
            'totalAnggota' => User::whereHas('jenisUser', fn ($q) => $q->whereIn('nama_role', ['mahasiswa', 'dosen']))->count(),
            'peminjamanAktif' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'terlambat' => Peminjaman::where('status', Peminjaman::DIPINJAM)->whereDate('tanggal_jatuh_tempo', '<', today())->count(),
            'totalDenda' => (int) Denda::where('status_bayar', Denda::BELUM_LUNAS)->sum('jumlah_denda'),

            'menungguVerifikasi' => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
            'reservasiMenunggu' => Reservasi::where('status', Reservasi::MENUNGGU)->count(),
            'feedbackBelumDibalas' => Feedback::where('status', Feedback::BELUM_DIBALAS)->count(),
            'taMenunggu' => TugasAkhir::where('status', TugasAkhir::MENUNGGU)->count(),
            'kunjunganHariIni' => Absensi::whereDate('tanggal', today())->count(),

            'grafik' => $grafik,
            'stokTersedia' => $stokTersedia,
            'sedangDipinjam' => $sedangDipinjam,

            'eventMendatang' => Event::withCount('peserta')
                ->whereDate('tanggal_mulai', '>=', today())
                ->orderBy('tanggal_mulai')
                ->first(),

            'antrianPeminjaman' => Peminjaman::with(['user.jenisUser', 'detail.buku'])
                ->where('status', Peminjaman::MENUNGGU)
                ->latest('id')
                ->take(5)
                ->get(),

            'peminjamanTerbaru' => Peminjaman::with(['user.jenisUser', 'detail.buku'])
                ->latest('id')
                ->take(6)
                ->get(),
        ]);
    }
}
