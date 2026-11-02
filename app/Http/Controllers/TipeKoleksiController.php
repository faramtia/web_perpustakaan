<?php

namespace App\Http\Controllers;

use App\Models\TipeKoleksi;

class TipeKoleksiController extends MasterController
{
    protected function config(): array
    {
        return [
            'model' => TipeKoleksi::class,
            'kolom' => 'nama_tipe',
            'judul' => 'Tipe Koleksi',
            'label' => 'Tipe koleksi',
            'ikon' => 'bi-tags',
            'route' => 'admin.tipe-koleksi',
            'deskripsi' => 'Jenis koleksi seperti novel, skripsi, jurnal penelitian, dan komik.',
            'placeholder' => 'mis. Skripsi',
            'relasi' => ['buku' => 'buku'],
        ];
    }
}
