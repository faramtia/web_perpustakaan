@php
    $links = [
        ['Beranda', 'home', 'home'],
        ['Katalog', 'katalog.index', 'katalog.*'],
        ['Tugas Akhir', 'tugas-akhir.index', 'tugas-akhir.*'],
        ['Event', 'event.index', 'event.*'],
        ['Artikel', 'artikel.index', 'artikel.*'],
        ['Gallery', 'gallery.index', 'gallery.*'],
        ['Tentang', 'about', 'about'],
    ];
@endphp

<header class="sticky top-0 z-30 bg-yellow-400 shadow-sm">
    <div class="mx-auto max-w-6xl px-4">
        <div class="flex h-16 items-center justify-between gap-4">

            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo Politeknik Negeri Lhokseumawe" class="h-11 w-11 rounded-full bg-white object-contain p-0.5">
                <div class="hidden leading-tight sm:block">
                    <p class="text-sm font-extrabold text-stone-900">PNL Library</p>
                    <p class="text-xs text-stone-700">Politeknik Negeri Lhokseumawe</p>
                </div>
            </a>

            <nav class="hidden items-center gap-1 text-sm font-semibold lg:flex">
                @foreach ($links as [$label, $route, $pola])
                    <a href="{{ route($route) }}"
                       class="rounded-lg px-3 py-2 transition {{ request()->routeIs($pola) ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-800 hover:bg-white/60' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="flex shrink-0 items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-dark"><i class="bi bi-speedometer2"></i> Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm hidden bg-white/70 text-stone-900 hover:bg-white sm:inline-flex">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-dark">Daftar</a>
                @endauth

                <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        class="grid h-10 w-10 place-items-center rounded-xl bg-white/60 text-lg text-stone-900 hover:bg-white lg:hidden" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>

        <nav id="mobileMenu" class="hidden flex-col gap-1 pb-4 text-sm font-semibold lg:hidden">
            @foreach ($links as [$label, $route, $pola])
                <a href="{{ route($route) }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs($pola) ? 'bg-white' : 'hover:bg-white/60' }}">{{ $label }}</a>
            @endforeach
            @guest
                <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 hover:bg-white/60">Masuk</a>
            @endguest
        </nav>
    </div>
</header>
