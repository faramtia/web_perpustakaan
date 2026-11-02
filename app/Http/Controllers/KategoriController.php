<?php

namespace App\Http\Controllers;

use App\Models\Kategori;

class KategoriController extends MasterController
{
    protected function config(): array
    {
        return [
            'model' => Kategori::class,
            'kolom' => 'nama_kategori',
            'judul' => 'Kategori Buku',
            'label' => 'Kategori',
            'ikon' => 'bi-grid',
            'route' => 'admin.kategori',
            'deskripsi' => 'Pengelompokan subjek untuk buku dan artikel.',
            'placeholder' => 'mis. Teknologi',
            'relasi' => ['buku' => 'buku'],
        ];
    }
}
