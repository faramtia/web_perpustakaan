<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\EjurnalController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\TipeKoleksiController;
use App\Http\Controllers\TugasAkhirController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (Pengunjung Umum) — tidak perlu login
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');

// Katalog, e-jurnal, artikel, dan daftar event bisa dilihat TANPA login
// (sesuai rancangan: Pengunjung Umum cuma bisa "lihat", bukan pinjam/daftar).
Route::get('/katalog', [BukuController::class, 'index'])->name('katalog.index');
Route::get('/ejurnal', [EjurnalController::class, 'index'])->name('ejurnal.index');
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/event', [EventController::class, 'index'])->name('event.index');

/*
|--------------------------------------------------------------------------
| Auth — Login & Register
|--------------------------------------------------------------------------
| Pendaftaran mandiri (register) cuma untuk mahasiswa & dosen.
| Akun admin/petugas dibuat oleh admin lewat menu Kelola User (belum
| diimplementasi di paket ini — tinggal tambah UserController kalau perlu).
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Kelola Buku — boleh Admin & Petugas (sesuai tabel hak akses)
    |--------------------------------------------------------------------------
    | Tetap pakai prefix/nama 'admin.' biar konsisten dengan link yang sudah
    | dipakai di view (admin.buku.create, dst), walau diakses juga oleh petugas.
    */
    Route::middleware('role:admin,petugas')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('buku', BukuController::class)->except(['index'])->names([
            'create' => 'buku.create', 'store' => 'buku.store',
            'edit' => 'buku.edit', 'update' => 'buku.update', 'destroy' => 'buku.destroy',
        ]);

        Route::get('/ejurnal/create', [EjurnalController::class, 'create'])->name('ejurnal.create');
        Route::post('/ejurnal', [EjurnalController::class, 'store'])->name('ejurnal.store');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
        Route::post('/lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
        Route::delete('/lokasi/{lokasi}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');

        Route::get('/tipe-koleksi', [TipeKoleksiController::class, 'index'])->name('tipe-koleksi.index');
        Route::post('/tipe-koleksi', [TipeKoleksiController::class, 'store'])->name('tipe-koleksi.store');
        Route::delete('/tipe-koleksi/{tipeKoleksi}', [TipeKoleksiController::class, 'destroy'])->name('tipe-koleksi.destroy');

        Route::post('/event', [EventController::class, 'store'])->name('event.store');

        Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
        Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');

        Route::get('/laporan', [AdminDashboardController::class, 'index'])->name('laporan.index');
    });

    /*
    |--------------------------------------------------------------------------
    | PETUGAS
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:petugas,admin')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/verifikasi', [PeminjamanController::class, 'verifikasi'])->name('peminjaman.verifikasi');
        Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback/{feedback}/balas', [FeedbackController::class, 'balas'])->name('feedback.balas');

        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir/{tugasAkhir}/review', [TugasAkhirController::class, 'review'])->name('tugas-akhir.review');

        Route::get('/event', [EventController::class, 'index'])->name('event.index');
    });

    /*
    |--------------------------------------------------------------------------
    | ANGGOTA (Mahasiswa & Dosen)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:mahasiswa,dosen')->prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/ajukan', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
        Route::post('/reservasi/{reservasi}/batal', [ReservasiController::class, 'batalkan'])->name('reservasi.batal');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

        Route::post('/event/{event}/daftar', [EventController::class, 'daftar'])->name('event.daftar');

        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir', [TugasAkhirController::class, 'store'])->name('tugas-akhir.store');
    });
});
