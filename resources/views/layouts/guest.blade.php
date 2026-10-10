<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Open Library PNL')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

    {{-- NAVBAR --}}
    <nav class="bg-yellow-500 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                        📚
                    </div>

                    <div>
                        <h1 class="font-bold text-white leading-tight">
                            PNL Library
                        </h1>
                        <p class="text-xs text-yellow-100">
                            Open Library
                        </p>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-6 text-sm font-medium">

                    <a href="{{ route('home') }}"
                       class="text-white hover:text-yellow-100">
                        Beranda
                    </a>

                    <a href="{{ route('katalog.index') }}"
                       class="text-white hover:text-yellow-100">
                        Katalog
                    </a>

                    <a href="{{ route('ejurnal.index') }}"
                       class="text-white hover:text-yellow-100">
                        E-Jurnal
                    </a>

                    <a href="{{ route('artikel.index') }}"
                       class="text-white hover:text-yellow-100">
                        Artikel
                    </a>

                    <a href="{{ route('event.index') }}"
                       class="text-white hover:text-yellow-100">
                        Event
                    </a>

                    @auth
                        @if(auth()->user()->role === 'petugas')
                            <a href="{{ route('petugas.dashboard') }}"
                               class="bg-white text-yellow-600 px-4 py-2 rounded-lg">
                                Dashboard
                            </a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="bg-white text-yellow-600 px-4 py-2 rounded-lg">
                                Dashboard
                            </a>
                        @elseif(in_array(auth()->user()->role, ['mahasiswa', 'dosen']))
                            <a href="{{ route('anggota.dashboard') }}"
                               class="bg-white text-yellow-600 px-4 py-2 rounded-lg">
                                Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-white text-yellow-600 px-4 py-2 rounded-lg hover:bg-yellow-50">
                            Login
                        </a>
                    @endauth

                </div>

            </div>
        </div>
    </nav>


    {{-- CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-5 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>


    {{-- FOOTER --}}
    <footer class="border-t bg-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
            © {{ date('Y') }} Perpustakaan Politeknik Negeri Lhokseumawe
        </div>
    </footer>

</body>
</html>