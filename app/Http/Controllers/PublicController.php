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
            'koleksiBaru' => Buku::orderBy('id', 'desc')->take(5)->get(),
            'koleksiPopuler' => Buku::withCount('detailPeminjaman')
                ->orderByDesc('detail_peminjaman_count')
                ->take(5)
                ->get(),
            'artikel' => Artikel::orderBy('tanggal_upload', 'desc')->take(3)->get(),
        ]);
    }
}
