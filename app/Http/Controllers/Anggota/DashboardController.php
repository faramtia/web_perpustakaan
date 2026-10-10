<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('anggota.dashboard', [
            'peminjamanAktif' => $user->peminjaman()
                ->where('status', Peminjaman::DIPINJAM)
                ->with('detail.buku')
                ->orderBy('tanggal_jatuh_tempo')
                ->get(),
            'reservasiSaya' => $user->reservasi()->with('buku')->latest('id')->take(5)->get(),
            'tugasAkhirSaya' => $user->tugasAkhir()->latest('id')->take(3)->get(),
        ]);
    }
}
