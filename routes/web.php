<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TipeKoleksiController;
use App\Http\Controllers\TugasAkhirController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (Pengunjung Umum)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang', [PublicController::class, 'about'])->name('about');

Route::get('/katalog', [BukuController::class, 'index'])->name('katalog.index');
Route::get('/tugas-akhir', [TugasAkhirController::class, 'publik'])->name('tugas-akhir.index');
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{artikel}', [ArtikelController::class, 'show'])->whereNumber('artikel')->name('artikel.show');
Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/{event}', [EventController::class, 'show'])->whereNumber('event')->name('event.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

/*
|--------------------------------------------------------------------------
| Kiosk absensi (satu komputer perpustakaan) — anggota mengetik NIM/NIP.
| Dibuka dari IP di KIOSK_ALLOWED_IPS, atau oleh petugas/admin yang login.
|--------------------------------------------------------------------------
*/
Route::middleware(['kiosk', 'throttle:60,1'])->prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'kiosk'])->name('absensi');
    Route::post('/absensi', [AbsensiController::class, 'kioskStore'])->name('absensi.store');
});

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

    // Pintasan: arahkan ke dashboard sesuai role.
    Route::get('/dashboard', [AuthenticatedSessionController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin & Petugas — kelola buku
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,petugas')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('buku', BukuController::class)->except('show');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN — master data, pengguna, konten, pengaturan
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
        Route::post('/lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
        Route::put('/lokasi/{lokasi}', [LokasiController::class, 'update'])->name('lokasi.update');
        Route::delete('/lokasi/{lokasi}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');

        Route::get('/tipe-koleksi', [TipeKoleksiController::class, 'index'])->name('tipe-koleksi.index');
        Route::post('/tipe-koleksi', [TipeKoleksiController::class, 'store'])->name('tipe-koleksi.store');
        Route::put('/tipe-koleksi/{tipeKoleksi}', [TipeKoleksiController::class, 'update'])->name('tipe-koleksi.update');
        Route::delete('/tipe-koleksi/{tipeKoleksi}', [TipeKoleksiController::class, 'destroy'])->name('tipe-koleksi.destroy');

        Route::resource('pengguna', PenggunaController::class)->except('show');

        Route::post('/event', [EventController::class, 'store'])->name('event.store');
        Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');

        Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
        Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
        Route::delete('/artikel/{artikel}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    /*
    |--------------------------------------------------------------------------
    | PETUGAS (admin juga boleh) — transaksi & layanan
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:petugas,admin')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/verifikasi', [PeminjamanController::class, 'verifikasi'])->name('peminjaman.verifikasi');
        Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::redirect('/pengembalian', '/petugas/peminjaman?tampil=belum')->name('pengembalian.index');

        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::post('/reservasi/{reservasi}/status', [ReservasiController::class, 'ubahStatus'])->name('reservasi.status');

        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
        Route::post('/denda', [DendaController::class, 'store'])->name('denda.store');
        Route::post('/denda/{denda}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback/{feedback}/balas', [FeedbackController::class, 'balas'])->name('feedback.balas');

        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir/{tugasAkhir}/review', [TugasAkhirController::class, 'review'])->name('tugas-akhir.review');

        Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::delete('/gallery', [GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{jenis}', [LaporanController::class, 'show'])
            ->whereIn('jenis', ['peminjaman', 'pengunjung', 'koleksi', 'denda'])
            ->name('laporan.show');
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

        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

        Route::post('/event/{event}/daftar', [EventController::class, 'daftar'])->name('event.daftar');

        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir', [TugasAkhirController::class, 'store'])->name('tugas-akhir.store');
    });
});
