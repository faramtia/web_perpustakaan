<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title') | Perpustakaan PNL</title>
</head>
<body class="flex min-h-screen items-center justify-center bg-stone-100 p-4 py-10 text-stone-800 antialiased">

    <div class="w-full max-w-md">
        <div class="mb-6 flex flex-col items-center text-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="mb-2 h-16 w-16 object-contain">
            </a>
            <h1 class="text-lg font-extrabold text-stone-900">Perpustakaan PNL</h1>
            <p class="text-sm text-stone-500">@yield('subtitle')</p>
        </div>

        <div class="card p-6 sm:p-8">
            <x-flash :show-errors="false" />
            @yield('content')
        </div>

        <p class="mt-6 text-center text-xs text-stone-400">
            <a href="{{ route('home') }}" class="hover:underline">&larr; Kembali ke beranda</a>
        </p>
    </div>

    @stack('scripts')
</body>
</html>
