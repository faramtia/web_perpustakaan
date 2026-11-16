<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Ubah sandi untuk pengguna yang sudah login (semua peran). */
class UbahSandiController extends Controller
{
    public function edit(): View
    {
        return view('akun.password');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password_lama' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8), 'different:password_lama'],
        ], [
            'password_lama.required' => 'Sandi lama wajib diisi.',
            'password_lama.current_password' => 'Sandi lama tidak sesuai.',
            'password.required' => 'Sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi sandi baru tidak sama.',
            'password.min' => 'Sandi baru minimal 8 karakter.',
            'password.different' => 'Sandi baru harus berbeda dari sandi lama.',
        ]);

        $user = $request->user();
        $user->forceFill(['password' => $request->password])->save(); // di-hash otomatis

        // Sesi di perangkat lain ikut keluar demi keamanan.
        Auth::logoutOtherDevices($request->password_lama);

        return back()->with('success', 'Sandi berhasil diubah.');
    }
}
