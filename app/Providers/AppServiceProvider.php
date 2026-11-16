<?php

namespace App\Providers;

use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Notifikasi reservasi: buku yang direservasi anggota dan kini sudah tersedia kembali.
        View::composer(['partials.sidebar', 'layouts.app', 'reservasi.index', 'dashboard.anggota'], function ($view) {
            static $cache = null;
            if ($cache === null) {
                $user = Auth::user();
                $cache = ($user && $user->isAnggota())
                    ? Reservasi::with('buku')
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['Menunggu', 'Diproses'])
                        ->whereHas('buku', fn ($q) => $q->where('stok', '>', 0))
                        ->get()
                    : collect();
            }
            $view->with('notifReservasi', $cache);
        });
    }
}
