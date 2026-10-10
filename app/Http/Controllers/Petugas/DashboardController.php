<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\TugasAkhir;
use App\Models\Feedback;

class DashboardController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard', [
            'menungguVerifikasi' => Peminjaman::where('status', 'pending')->count(),
            'taMenunggu' => TugasAkhir::where('status', 'diajukan')->count(),
            'feedbackBaru' => Feedback::where('status', 'baru')->count(),
            'antrianPeminjaman' => Peminjaman::with(['user', 'detail.buku'])
                ->where('status', 'pending')
                ->orderBy('id', 'desc')
                ->take(5)
                ->get(),
        ]);
    }
}
