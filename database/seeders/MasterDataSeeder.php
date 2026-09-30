<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\TipeKoleksi;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = ['Teknologi Informasi', 'Ilmu Sosial', 'Bahasa', 'Teknik Elektro', 'Manajemen'];
        foreach ($kategoriList as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }

        $lokasiList = ['Ruang Sirkulasi', 'Ruang Referensi', 'Ruang Terbitan Berkala'];
        foreach ($lokasiList as $nama) {
            Lokasi::firstOrCreate(['nama_ruang' => $nama]);
        }

        $tipeList = ['Buku Cetak', 'E-Book', 'E-Jurnal', 'E-TGA'];
        foreach ($tipeList as $nama) {
            TipeKoleksi::firstOrCreate(['nama_tipe' => $nama]);
        }

        // Beberapa buku contoh biar dashboard tidak kosong melompong
        if (Buku::count() === 0) {
            $kategori = Kategori::first();
            $lokasi = Lokasi::first();
            $tipe = TipeKoleksi::where('nama_tipe', 'Buku Cetak')->first();

            $contoh = [
                ['judul' => 'Belajar Laravel dari Nol', 'penulis' => 'Tim Penulis', 'stok' => 5],
                ['judul' => 'Basis Data Modern', 'penulis' => 'Tim Penulis', 'stok' => 3],
                ['judul' => 'Dasar Pemrograman Web', 'penulis' => 'Tim Penulis', 'stok' => 4],
            ];

            foreach ($contoh as $data) {
                Buku::create([
                    'kategori_id' => $kategori->id,
                    'lokasi_id' => $lokasi->id,
                    'tipe_koleksi_id' => $tipe->id,
                    'judul' => $data['judul'],
                    'penulis' => $data['penulis'],
                    'stok' => $data['stok'],
                ]);
            }
        }
    }
}
