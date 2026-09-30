<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller{
    public function index(){
        $user = Auth::user();

        return view('anggota.dashboard', [
            'peminjamanAktif' => $user->peminjaman()->where('status', 'aktif')->with('detail.buku')->get(),

        ]);
    }
}