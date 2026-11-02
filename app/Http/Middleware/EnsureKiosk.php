<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Halaman kiosk (absensi di komputer perpustakaan) boleh dibuka kalau:
 *  - yang membuka adalah petugas/admin yang sudah login, ATAU
 *  - alamat IP komputernya terdaftar di KIOSK_ALLOWED_IPS (.env).
 */
class EnsureKiosk
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $petugasLogin = $user && in_array($user->role, ['admin', 'petugas'], true);

        $ips = config('perpus.kiosk_ips', []);
        $ipDiizinkan = ! empty($ips) && IpUtils::checkIp((string) $request->ip(), $ips);

        abort_unless($petugasLogin || $ipDiizinkan, 403, 'Halaman ini hanya bisa dibuka dari komputer perpustakaan.');

        return $next($request);
    }
}
