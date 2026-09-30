<header class="flex items-center justify-between bg-white border-b px-4 sm:px-6 py-3 sticky top-0 z-10">

    <div class="flex items-center gap-3">
        @auth
            <button
                onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                class="lg:hidden text-gray-500 hover:text-gray-700"
                aria-label="Buka menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        @endauth

        <h1 class="text-base sm:text-lg font-semibold text-gold-700">
            @yield('page-title', 'Perpustakaan PNL')
        </h1>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        @guest
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gold-700">Masuk</a>
            <a href="{{ route('register') }}" class="text-sm font-medium bg-gold-600 text-white px-4 py-2 rounded-lg hover:bg-gold-700">Daftar</a>
        @else
            <span class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                {{ auth()->user()->name }}
                <span class="text-xs bg-gold-100 text-gold-700 px-2 py-0.5 rounded-full capitalize">{{ auth()->user()->role }}</span>
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-600 font-medium">
                    Keluar
                </button>
            </form>
        @endguest
    </div>
</header>
