@extends('layouts.auth')
@section('title', 'Password Baru')
@section('subtitle', 'Buat password baru untuk akunmu')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required readonly
                   class="input bg-stone-50 @error('email') !border-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="label">Password baru</label>
            <input id="password" type="password" name="password" required minlength="8" autofocus autocomplete="new-password"
                   class="input @error('password') !border-red-400 @enderror">
            <p class="mt-1 text-xs text-stone-400">Minimal 8 karakter.</p>
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">Ulangi password baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input">
        </div>

        <button type="submit" class="btn btn-primary w-full">Simpan Password Baru</button>
    </form>
@endsection
