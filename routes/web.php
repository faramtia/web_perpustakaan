<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
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
| Halaman Publik (Pengunjung Umum)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/katalog', [BukuController::class, 'index'])->name('katalog.index');
Route::get('/ejurnal', [EjurnalController::class, 'index'])->name('ejurnal.index');
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

/*
|--------------------------------------------------------------------------
| Auth — Login & Register
|--------------------------------------------------------------------------
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
    | Kelola Buku — Admin & Petugas
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,petugas')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('buku', BukuController::class)->names([
            'index' => 'buku.index',
            'create' => 'buku.create', 
            'store' => 'buku.store',
            'edit' => 'buku.edit',
            'update' => 'buku.update',
            'destroy' => 'buku.destroy',
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
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
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
        Route::redirect('/pengembalian', '/petugas/peminjaman?tampil=belum')->name('pengembalian.index');

        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback/{feedback}/balas', [FeedbackController::class, 'balas'])->name('feedback.balas');

        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
        Route::post('/denda/{id}/bayar', [DendaController::class, 'bayar'])->name('petugas.denda.index');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');

        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::delete('/gallery', [GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir/{tugasAkhir}/review', [TugasAkhirController::class, 'review'])->name('tugas-akhir.review');

        Route::get('/event', [EventController::class, 'index'])->name('event.index');
        Route::get('/event/detail', function () {
            return view('event.show');
        });
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