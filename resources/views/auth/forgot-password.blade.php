@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('subtitle', 'Atur ulang password lewat email')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4" novalidate>
        @csrf
        <p class="text-sm text-stone-500">Masukkan email akunmu. Kami akan mengirim tautan untuk membuat password baru.</p>

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input @error('email') !border-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">Kirim Tautan Reset</button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Ingat password? <a href="{{ route('login') }}" class="font-semibold text-yellow-700 hover:underline">Kembali masuk</a>
    </p>
@endsection
