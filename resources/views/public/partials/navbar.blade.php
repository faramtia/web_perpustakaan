<header class="bg-white border-b-4 border-gold-500 sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 gap-4">

            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo Politeknik Negeri Lhokseumawe" class="h-11 w-11 object-contain">
                <div class="leading-tight hidden sm:block">
                    <p class="font-bold text-gray-800 text-sm">Perpustakaan</p>
                    <p class="text-xs text-gray-500">Politeknik Negeri Lhokseumawe</p>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gold-700">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="hover:text-gold-700">Katalog</a>
                <a href="{{ route('ejurnal.index') }}" class="hover:text-gold-700">E-Jurnal</a>
                <a href="{{ route('event.index') }}" class="hover:text-gold-700">Event</a>
                <a href="{{ route('artikel.index') }}" class="hover:text-gold-700">Artikel & Berita</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @auth
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'admin' => 'admin.dashboard',
                            'petugas' => 'petugas.dashboard',
                            default => 'anggota.dashboard',
                        };
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                       class="text-sm font-semibold text-white bg-gray-800 px-4 py-2 rounded-full hover:bg-gray-900">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-block text-sm font-semibold text-gold-700 border border-gold-500 px-4 py-2 rounded-full hover:bg-gold-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold text-white bg-gray-800 px-4 py-2 rounded-full hover:bg-gray-900">
                        Daftar
                    </a>
                @endauth

                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <nav id="mobileMenu" class="hidden lg:hidden pb-4 flex flex-col gap-2 text-sm font-medium text-gray-600">
            <a href="{{ route('home') }}" class="py-1">Beranda</a>
            <a href="{{ route('katalog.index') }}" class="py-1">Katalog</a>
            <a href="{{ route('ejurnal.index') }}" class="py-1">E-Jurnal</a>
            <a href="{{ route('event.index') }}" class="py-1">Event</a>
            <a href="{{ route('artikel.index') }}" class="py-1">Artikel & Berita</a>
            @guest
                <a href="{{ route('login') }}" class="py-1 font-semibold text-gold-700">Masuk</a>
            @endguest
        </nav>
    </div>
</header>
