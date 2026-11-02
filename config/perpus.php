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
];
