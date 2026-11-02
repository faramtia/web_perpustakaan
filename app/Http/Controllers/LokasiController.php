<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;

class LokasiController extends MasterController
{
    protected function config(): array
    {
        return [
            'model' => Lokasi::class,
            'kolom' => 'nama_ruang',
            'judul' => 'Lokasi Rak',
            'label' => 'Lokasi',
            'ikon' => 'bi-geo-alt',
            'route' => 'admin.lokasi',
            'deskripsi' => 'Ruang dan rak tempat koleksi disimpan.',
            'placeholder' => 'mis. Rak A1 Lantai 1',
            'relasi' => ['buku' => 'buku'],
        ];
    }
}
