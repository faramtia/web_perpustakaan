# Paket Web Perpustakaan PNL (Laravel)

Isi zip ini adalah file TAMBAHAN/PENGGANTI untuk project Laravel `web_perpustakaan`
kalian. Struktur foldernya sama dengan project, jadi tinggal salin & timpa.

## Cara Pasang

1. Ekstrak zip ini, lalu **salin semua isinya ke root project** (gabungkan/timpa folder
   `app`, `bootstrap`, `database`, `public`, `resources`, `routes`).
   - File yang **menimpa** bawaan Laravel: `bootstrap/app.php`, `routes/web.php`,
     `app/Models/User.php`, `database/seeders/DatabaseSeeder.php`.
   - Hapus `resources/views/welcome.blade.php` kalau mau (sudah tidak dipakai).
2. Pastikan `.env` sudah diarahkan ke database MySQL kalian.
3. Jalankan:

```
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

4. Buka http://localhost:8000

## Akun Contoh (password semua: `password`)

| Role      | Email               |
|-----------|---------------------|
| Admin     | admin@pnl.ac.id     |
| Petugas   | petugas@pnl.ac.id   |
| Mahasiswa | mahasiswa@pnl.ac.id |
| Dosen     | dosen@pnl.ac.id     |

## Yang Sudah Jalan

- Beranda pengunjung umum (gaya Tel-U Open Library, identitas PNL)
- Login & Sign Up (daftar mandiri hanya Mahasiswa/Dosen)
- Dashboard beda untuk Admin, Petugas, Mahasiswa, Dosen
- Katalog & CRUD buku, master kategori/lokasi/tipe koleksi
- Alur peminjaman: ajukan -> verifikasi -> tandai kembali (denda otomatis Rp2.000/hari)
- Reservasi, absensi manual, e-jurnal (abstrak), feedback/tanya pustakawan,
  event + pendaftaran, upload & review tugas akhir, artikel/blog

## Belum Ada (tugas lanjutan kelompok)

- Kelola User oleh admin (buat akun petugas/admin lewat UI)
- Laporan & grafik (menu Laporan sementara mengarah ke dashboard admin)
- Absensi via scan QR, notifikasi jatuh tempo, export PDF/Excel
- Reservasi belum otomatis berubah "tersedia" saat buku dikembalikan

## Catatan

- Tailwind memakai CDN (perlu internet saat dibuka di browser). Ganti ke build Vite
  kalau mau production.
- Kode belum sempat dijalankan penuh di mesin saya (tidak ada PHP di lingkungan
  pembuatan), jadi kalau ada error saat `migrate`/`serve`, kirim pesan errornya
  ke saya dan aku bantu perbaiki.
