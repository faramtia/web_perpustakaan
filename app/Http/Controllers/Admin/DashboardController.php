<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Reservasi;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalBuku' => Buku::sum('stok'),
            'peminjamanAktif' => Peminjaman::where('status', 'aktif')->count(),
            'reservasiMenunggu' => Reservasi::where('status', 'menunggu')->count(),
            'kunjunganHariIni' => Absensi::whereDate('tanggal', today())->count(),
            'peminjamanTerbaru' => Peminjaman::with(['user', 'detail.buku'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
