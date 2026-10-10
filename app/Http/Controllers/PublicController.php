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
<<<<<<< HEAD
            'koleksiBaru' => Buku::orderBy('id', 'desc')->take(5)->get(),
=======

            'koleksiBaru' => Buku::orderByDesc('tahun_terbit')
                ->take(3)
                ->get(),

>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
            'koleksiPopuler' => Buku::withCount('detailPeminjaman')
                ->orderByDesc('detail_peminjaman_count')
                ->take(3)
                ->get(),

            'artikel' => Artikel::with('kategori')->orderByDesc('tanggal_upload')
                ->take(3)
                ->get(),
<<<<<<< HEAD
            'artikel' => Artikel::orderBy('tanggal_upload', 'desc')->take(3)->get(),
=======
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
        ]);
    }
}