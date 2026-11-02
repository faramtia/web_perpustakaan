@extends('layouts.auth')
@section('title', 'Daftar')
@section('subtitle', 'Daftar sebagai anggota (Mahasiswa/Dosen)')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
        @csrf

        <div>
            <label class="label">Daftar sebagai</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($roles as $role)
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-stone-200 py-2.5 text-sm has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                        <input type="radio" name="jenis_user_id" value="{{ $role->id }}"
                               @checked(old('jenis_user_id', $roles->first()?->id) == $role->id)
                               class="text-yellow-500 focus:ring-yellow-300">
                        <span class="capitalize">{{ strtolower($role->nama_role) }}</span>
                    </label>
                @endforeach
            </div>
            @error('jenis_user_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @foreach ([
            ['nama', 'Nama Lengkap', 'text', 'name', null],
            ['nim_nip', 'NIM / NIP', 'text', 'off', 'numeric'],
            ['email', 'Email', 'email', 'email', null],
        ] as [$nama, $label, $tipe, $auto, $mode])
            <div>
                <label for="{{ $nama }}" class="label">{{ $label }}</label>
                <input id="{{ $nama }}" type="{{ $tipe }}" name="{{ $nama }}" value="{{ old($nama) }}" required autocomplete="{{ $auto }}"
                       @if ($mode) inputmode="{{ $mode }}" @endif
                       class="input @error($nama) !border-red-400 @enderror">
                @error($nama)
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div>
            <label for="password" class="label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="input @error('password') !border-red-400 @enderror">
            <p class="hint">Minimal 8 karakter, kombinasi huruf dan angka.</p>
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input">
        </div>

        <button type="submit" class="btn btn-primary w-full">Daftar</button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-yellow-700 hover:underline">Masuk di sini</a>
    </p>
@endsection
