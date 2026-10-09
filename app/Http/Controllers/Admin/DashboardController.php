<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Reservasi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalBuku' => Buku::sum('stok'),
            'peminjamanAktif' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'reservasiMenunggu' => Reservasi::where('status', Reservasi::MENUNGGU)->count(),
            'kunjunganHariIni' => Absensi::whereDate('tanggal', today())->count(),
            'peminjamanTerbaru' => Peminjaman::with(['user.jenisUser', 'detail.buku'])
                ->latest('id')
                ->take(5)
                ->get(),
        ]);
    }
}
