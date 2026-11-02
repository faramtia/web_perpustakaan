<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Buku;
use App\Models\Event;
use App\Models\TugasAkhir;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home', [
            'totalKoleksi' => (int) Buku::hanyaBuku()->sum('stok'),
            'totalJudul' => Buku::hanyaBuku()->count(),
            'totalTugasAkhir' => TugasAkhir::where('status', TugasAkhir::DISETUJUI)->count(),

            'koleksiBaru' => Buku::hanyaBuku()->with('kategori')->orderByDesc('id')->take(5)->get(),

            'koleksiPopuler' => Buku::hanyaBuku()->with('kategori')
                ->withCount('detailPeminjaman')
                ->orderByDesc('detail_peminjaman_count')
                ->orderBy('judul')
                ->take(5)
                ->get(),

            'artikel' => Artikel::with(['kategori', 'penulis'])
                ->orderByDesc('tanggal_upload')
                ->orderByDesc('id')
                ->take(3)
                ->get(),

            'eventMendatang' => Event::withCount('peserta')
                ->whereDate('tanggal_mulai', '>=', today())
                ->orderBy('tanggal_mulai')
                ->take(3)
                ->get(),
        ]);
    }

    /**
     * Halaman "Tentang Kami" (About) untuk pengunjung umum.
     * Data diambil dari situs resmi PNL: library.pnl.ac.id & pustaka.pnl.ac.id.
     */
    public function about()
    {
        $foto = 'https://library.pnl.ac.id/images/persons/';

        return view('public.about', [
            'pustakawan' => [
                ['nama' => 'Mulyadi Alamsyah', 'jabatan' => 'Pustakawan Senior', 'surel' => null, 'sosmed' => null, 'foto' => $foto.'user_mulyadi.JPG'],
                ['nama' => 'Safriah', 'jabatan' => 'Pustakawan Senior', 'surel' => 'safriah0105@gmail.com', 'sosmed' => null, 'foto' => $foto.'user_safriah.JPG'],
                ['nama' => 'Nurul Hayati', 'jabatan' => 'Pustakawan Senior', 'surel' => null, 'sosmed' => null, 'foto' => $foto.'user_nurul.png'],
                ['nama' => 'Usman', 'jabatan' => 'Pustakawan Senior', 'surel' => null, 'sosmed' => null, 'foto' => $foto.'user_usman.JPG'],
                ['nama' => 'Amiruddin, S.I.Pust.', 'jabatan' => 'Pustakawan Senior', 'surel' => 'aminuddin@pnl.ac.id', 'sosmed' => null, 'foto' => $foto.'user_amir.jpg'],
                ['nama' => 'Maryani', 'jabatan' => 'Pustakawan Senior', 'surel' => null, 'sosmed' => null, 'foto' => $foto.'user_maryani.JPG'],
                ['nama' => 'Fauzah', 'jabatan' => 'Pustakawan', 'surel' => null, 'sosmed' => 'YouTube: pnl', 'foto' => $foto.'user_fauzah.png'],
                ['nama' => 'Rizki Dimas Sagara Nasution', 'jabatan' => 'Pustakawan', 'surel' => 'dimassagaranst@gmail.com', 'sosmed' => null, 'foto' => null],
            ],
        ]);
    }
}
