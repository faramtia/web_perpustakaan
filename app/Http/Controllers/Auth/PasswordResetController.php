<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as AturanPassword;
use Illuminate\View\View;

/** Lupa sandi: kirim tautan reset ke email, lalu setel sandi baru lewat tautan itu. */
class PasswordResetController extends Controller
{
    public function formLupa(): View
    {
        return view('auth.forgot-password');
    }

    public function kirimTautan(Request $request): RedirectResponse
    {
        $request->validate(
            ['email' => ['required', 'email']],
            ['email.required' => 'Email wajib diisi.', 'email.email' => 'Format email tidak valid.']
        );

        $status = Password::sendResetLink($request->only('email'));

        // Pesan sama untuk email terdaftar maupun tidak, supaya email anggota tidak bisa ditebak.
        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput()->withErrors(['email' => 'Tunggu sebentar sebelum meminta tautan lagi.']);
        }

        return back()->with('success', 'Jika email terdaftar, tautan untuk mengatur ulang sandi sudah dikirim. Cek kotak masuk (dan folder spam).');
    }

    public function formReset(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function simpanReset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', AturanPassword::min(8)],
        ], [
            'password.required' => 'Sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi sandi tidak sama.',
            'password.min' => 'Sandi minimal 8 karakter.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password, // di-hash otomatis oleh cast 'hashed'
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Tautan tidak valid atau sudah kedaluwarsa. Minta tautan baru.']);
        }

        return redirect()->route('login')->with('success', 'Sandi berhasil diubah. Silakan masuk dengan sandi baru.');
    }
}
