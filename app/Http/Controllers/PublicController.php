<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Buku;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home', [
            'totalKoleksi' => Buku::sum('stok'),
            'totalJudul' => Buku::count(),
            'koleksiBaru' => Buku::latest()->take(5)->get(),
            'koleksiPopuler' => Buku::withCount('detailPeminjaman')
                ->orderByDesc('detail_peminjaman_count')
                ->take(5)
                ->get(),
            'artikel' => Artikel::latest('tanggal_terbit')->take(3)->get(),
        ]);
    }
}
