<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        // Pendaftaran mandiri cuma untuk mahasiswa & dosen.
        // Akun admin/petugas dibuat langsung oleh admin lewat menu Kelola User.
        $roles = JenisUser::whereIn('nama_role', ['mahasiswa', 'dosen'])->get();

        return view('auth.register', ['roles' => $roles]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validRoleIds = JenisUser::whereIn('nama_role', ['mahasiswa', 'dosen'])->pluck('id');

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nim_nip' => ['required', 'string', 'max:30'],
            'jenis_user_id' => ['required', 'in:'.$validRoleIds->implode(',')],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'jenis_user_id' => $request->jenis_user_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('anggota.dashboard');
    }
}
