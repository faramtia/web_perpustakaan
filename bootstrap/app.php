<?php

use App\Http\Middleware\EnsureKiosk;
use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureRole::class,
            'kiosk' => EnsureKiosk::class,
        ]);

        // Pengguna yang sudah login dan membuka /login diarahkan ke dashboard sesuai role-nya.
        $middleware->redirectUsersTo('/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
