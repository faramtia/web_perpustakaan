@extends('layouts.auth')
@section('title', 'Masuk')
@section('subtitle', 'Masuk ke akun kamu')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
        @csrf

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="input @error('email') !border-red-400 @enderror">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="input pr-20 @error('password') !border-red-400 @enderror">
                <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-stone-500 hover:text-yellow-700">Lihat</button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-stone-500">
            <input type="checkbox" name="remember" class="rounded border-stone-300 text-yellow-500 focus:ring-yellow-300" @checked(old('remember'))>
            Ingat saya
        </label>
        <a href="{{ route('password.request') }}" class="-mt-2 block text-right text-xs font-semibold text-yellow-700 hover:underline">Lupa password?</a>

        <button type="submit" class="btn btn-primary w-full">Masuk</button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-yellow-700 hover:underline">Daftar sebagai Mahasiswa/Dosen</a>
    </p>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const tampil = input.type === 'password';
        input.type = tampil ? 'text' : 'password';
        this.textContent = tampil ? 'Sembunyikan' : 'Lihat';
    });
</script>
@endpush
