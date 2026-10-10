# Web Perpustakaan PNL (Laravel 12)

Sumber kebenaran database project ini adalah **file SQL**, bukan migrasi Laravel.

## Cara Menjalankan

1. Buat database kosong `web_perpustakaan` di MySQL (Laragon / phpMyAdmin).
2. Import `database/sql/web_perpustakaan.sql`.
3. Salin `.env.example` menjadi `.env`, lalu isi `DB_PASSWORD` kalau MySQL kamu memakai password.
4. Jalankan:

```
composer install
php artisan key:generate
php artisan storage:link
php artisan serve
```

5. Buka http://localhost:8000

> **Jangan jalankan `php artisan migrate:fresh`.** Perintah itu menghapus semua tabel
> lalu membuat ulang dari migrasi lama, sehingga data dan struktur database ikut hilang.

## Password Akun

Password di database harus berupa hash bcrypt. Kalau kamu baru mengimpor data lama
yang passwordnya masih teks biasa, jalankan sekali:

```
php artisan users:hash-passwords
```

## Aturan Peminjaman

- Lama pinjam dan tarif denda per hari diatur per role di tabel `jenis_user`
  (`lama_pinjam_hari`, `denda_per_hari`).
- Alur status peminjaman: `Menunggu` -> `Dipinjam` -> `Dikembalikan` (atau `Ditolak`).
- Peminjaman yang sudah lewat jatuh tempo tampil sebagai "Terlambat". Saat buku dikembalikan,
  denda keterlambatan otomatis dicatat di tabel `denda`.

## Mengubah Struktur Database

Ubah lewat SQL (`ALTER TABLE ...`), lalu export ulang dump ke `database/sql/web_perpustakaan.sql`
supaya anggota kelompok lain mendapat struktur yang sama.

## Catatan

- Tailwind memakai CDN, jadi butuh internet saat dibuka di browser.
- Folder `database/migrations_lama` (kalau ada) hanya arsip dan tidak dipakai.
