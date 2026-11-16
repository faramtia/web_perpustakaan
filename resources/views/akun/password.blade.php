@extends('layouts.app')
@section('title', 'Ubah Sandi')

@section('content')
    <x-page-header icon="bi-key" title="Ubah Sandi" subtitle="Ganti sandi akunmu secara berkala agar tetap aman." />

    <form method="POST" action="{{ route('password.ubah.update') }}" class="card max-w-lg space-y-4 p-6">
        @csrf @method('PUT')

        <div>
            <label class="label">Sandi lama</label>
            <input type="password" name="password_lama" required autocomplete="current-password"
                   class="input @error('password_lama') !border-red-400 @enderror">
            @error('password_lama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label">Sandi baru</label>
            <input type="password" name="password" required minlength="8" autocomplete="new-password"
                   class="input @error('password') !border-red-400 @enderror">
            <p class="mt-1 text-xs text-stone-400">Minimal 8 karakter dan berbeda dari sandi lama.</p>
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label">Ulangi sandi baru</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password" class="input">
        </div>

        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Sandi</button>
    </form>
@endsection
