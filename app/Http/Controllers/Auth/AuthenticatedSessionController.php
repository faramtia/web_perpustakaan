<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

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