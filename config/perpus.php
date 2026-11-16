<?php

return [
    /*
    | Komputer perpustakaan yang boleh membuka halaman kiosk absensi (/kiosk/absensi)
    | TANPA login. Isi di .env, pisahkan dengan koma. Mendukung IP biasa dan CIDR.
    | Contoh (satu komputer absensi): KIOSK_ALLOWED_IPS=192.168.1.20
    | Kalau aplikasi dijalankan di komputer absensi itu sendiri: KIOSK_ALLOWED_IPS=127.0.0.1
    | Kalau kosong, halaman kiosk hanya bisa dibuka oleh petugas/admin yang sudah login.
    */
    'kiosk_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('KIOSK_ALLOWED_IPS', ''))))),

    /*
    | Ketentuan denda buku rusak / hilang (Rupiah). Nominal ini hanya DEFAULT yang terisi
    | otomatis di form "Catat denda"; petugas masih bisa mengubahnya per kasus.
    | Sesuaikan dengan kebijakan Perpustakaan PNL.
    */
    'denda_kerusakan' => [
        'ringan' => ['label' => 'Rusak ringan', 'jenis' => 'rusak', 'nominal' => 10000,
            'contoh' => 'Coretan, sampul lecet, halaman terlipat.'],
        'sedang' => ['label' => 'Rusak sedang', 'jenis' => 'rusak', 'nominal' => 25000,
            'contoh' => 'Halaman sobek atau basah, jilid longgar.'],
        'berat' => ['label' => 'Rusak berat', 'jenis' => 'rusak', 'nominal' => 50000,
            'contoh' => 'Banyak halaman lepas atau hilang, buku tidak layak pakai.'],
        'hilang' => ['label' => 'Hilang', 'jenis' => 'hilang', 'nominal' => 75000,
            'contoh' => 'Buku tidak dikembalikan; ganti buku yang sama atau bayar sesuai harga buku.'],
    ],
];
