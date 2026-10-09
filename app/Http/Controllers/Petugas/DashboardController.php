<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Peminjaman;
use App\Models\TugasAkhir;

class DashboardController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard', [
            'menungguVerifikasi' => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
            'taMenunggu' => TugasAkhir::where('status', TugasAkhir::MENUNGGU)->count(),
            'feedbackBaru' => Feedback::where('status', Feedback::BELUM_DIBALAS)->count(),
            'antrianPeminjaman' => Peminjaman::with(['user.jenisUser', 'detail.buku'])
                ->where('status', Peminjaman::MENUNGGU)
                ->latest('id')
                ->take(5)
                ->get(),
        ]);
    }
}
