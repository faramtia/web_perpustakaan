<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Pendaftaran mandiri hanya untuk mahasiswa & dosen.
     * Akun admin/petugas dibuat langsung oleh admin.
     * LOWER() dipakai karena penulisan role di database tidak seragam.
     */
    private function rolesAnggota()
    {
        return JenisUser::whereIn(DB::raw('LOWER(nama_role)'), ['mahasiswa', 'dosen'])->get();
    }

    public function create()
    {
        return view('auth.register', ['roles' => $this->rolesAnggota()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validRoleIds = $this->rolesAnggota()->pluck('id')->all();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nim_nip' => ['required', 'string', 'max:50', 'unique:user,nim_nip'],
            'jenis_user_id' => ['required', Rule::in($validRoleIds)],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:user,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'nim_nip.required' => 'NIM / NIP wajib diisi.',
            'nim_nip.unique' => 'NIM / NIP ini sudah terdaftar.',
            'jenis_user_id.required' => 'Pilih daftar sebagai Mahasiswa atau Dosen.',
            'jenis_user_id.in' => 'Jenis anggota tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
        ]);

        // Password otomatis di-hash oleh cast 'hashed' di model User.
        $user = User::create([
            'nama' => $data['nama'],
            'nim_nip' => $data['nim_nip'],
            'jenis_user_id' => $data['jenis_user_id'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('anggota.dashboard');
    }
}
