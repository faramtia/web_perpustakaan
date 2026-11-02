# Web Perpustakaan PNL (Laravel 12)

Sistem informasi perpustakaan kampus dengan tiga peran: **Admin**, **Petugas**, **Anggota** (Mahasiswa / Dosen).
Sumber kebenaran database adalah **file SQL**, bukan migrasi Laravel.

## Cara Menjalankan

1. Buat database kosong `web_perpustakaan` di MySQL (Laragon / phpMyAdmin).
2. Import `database/sql/web_perpustakaan.sql` (sudah termasuk perubahan struktur terbaru).
   - Kalau database lama sudah terlanjur dipakai, cukup jalankan `database/sql/perbaikan_sinkron.sql`
     (aman dijalankan berulang).
3. Salin `.env.example` menjadi `.env`, isi `DB_PASSWORD` bila perlu. **Jangan commit `.env`.**
4. Jalankan:

```
composer install
php artisan key:generate
php artisan storage:link
php artisan serve
```

5. Buka http://localhost:8000

> **Jangan jalankan `php artisan migrate:fresh`.** Perintah itu menghapus tabel lalu membuat ulang
> dari migrasi lama, sehingga struktur dan data database ikut hilang.

## Akun

| Role | Login | Password |
|---|---|---|
| Admin | admin.pnl@ac.id | (sesuai data lama kelompok) |
| Petugas | petugas.pnl@ac.id | petugas123 |
| Dosen | dosen.pnl@ac.id | dosen123 |

Akun petugas dan dosen hanyalah contoh untuk pengujian. **Ganti password sebelum dipublikasikan.**
Password di database harus berupa hash bcrypt.

## Aturan Peminjaman

- Lama pinjam dan denda per hari diatur per role di tabel `jenis_user` (menu Admin > Pengaturan).
- Alur: `Menunggu` -> `Dipinjam` -> `Dikembalikan` (atau `Ditolak`). Dipinjam yang lewat jatuh tempo tampil "Terlambat";
  denda keterlambatan otomatis dicatat saat buku dikembalikan.
- Maksimal 3 buku per pengajuan.

## Hak Akses

- **Admin**: seluruh fitur, termasuk master data, pengguna, event, artikel, pengaturan.
- **Petugas**: buku, e-jurnal, verifikasi peminjaman/pengembalian, reservasi, denda, absensi, feedback, review tugas akhir, galeri, laporan.
- **Anggota**: ajukan peminjaman, reservasi, lihat denda & absensi, feedback, daftar event, unggah tugas akhir.

## Mengubah Struktur Database

Ubah lewat SQL (`ALTER TABLE ...`), lalu export ulang dump ke `database/sql/web_perpustakaan.sql`.

## Catatan

- Tailwind dan Bootstrap Icons memakai CDN, jadi butuh internet saat dibuka di browser.
