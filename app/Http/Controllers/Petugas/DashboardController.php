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
<<<<<<< HEAD
            'menungguVerifikasi' => Peminjaman::where('status', 'pending')->count(),
            'taMenunggu' => TugasAkhir::where('status', 'diajukan')->count(),
            'feedbackBaru' => Feedback::where('status', 'baru')->count(),
            'antrianPeminjaman' => Peminjaman::with(['user', 'detail.buku'])
                ->where('status', 'pending')
                ->orderBy('id', 'desc')
=======
            'menungguVerifikasi' => Peminjaman::where('status', Peminjaman::MENUNGGU)->count(),
            'taMenunggu' => TugasAkhir::where('status', TugasAkhir::MENUNGGU)->count(),
            'feedbackBaru' => Feedback::where('status', Feedback::BELUM_DIBALAS)->count(),
            'antrianPeminjaman' => Peminjaman::with(['user.jenisUser', 'detail.buku'])
                ->where('status', Peminjaman::MENUNGGU)
                ->latest('id')
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
                ->take(5)
                ->get(),
        ]);
    }
}
