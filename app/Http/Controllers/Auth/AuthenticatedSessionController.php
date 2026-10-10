<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Batasi percobaan login: maksimal 5x per menit untuk kombinasi email + IP.
        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $detik = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$detik} detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

<<<<<<< HEAD
        // DIUBAH: tujuan redirect ditentukan dari nama role (jenis_user.nama_role),
        // bukan dari angka id yang bisa berbeda di tiap database.
        $path = $this->redirectPathForRole(Auth::user()->role);

        if ($path === null) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Peran akun tidak dikenali. Cek isi tabel jenis_user dan kolom jenis_user_id di tabel user.',
            ])->onlyInput('email');
        }

        return redirect()->intended($path);
=======
        $role = Auth::user()->role;

        // Kalau user diarahkan dari halaman lain, pastikan halaman itu memang boleh diakses rolenya.
        return redirect()->intended($this->redirectPathForRole($role));
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectPathForRole(?string $role): ?string
    {
        return match ($role) {
            'admin'               => route('admin.dashboard'),
            'petugas'             => route('petugas.dashboard'),
            'mahasiswa', 'dosen'  => route('anggota.dashboard'),
            default               => null,
        };
    }
}