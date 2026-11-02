<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title', 'Perpustakaan PNL') | Perpustakaan PNL</title>
</head>
@php
    $user = auth()->user();
    $labelRole = [
        'admin' => 'Administrator',
        'petugas' => 'Petugas Perpustakaan',
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
    ][$user->role] ?? ucfirst((string) $user->role);
    $inisial = strtoupper(mb_substr(trim($user->nama ?? 'U'), 0, 2));
@endphp
<body class="bg-stone-100 text-stone-800 antialiased">

    {{-- SIDEBAR (menu mengikuti role) --}}
    @include('partials.sidebar')
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 z-20 hidden bg-stone-900/40 lg:hidden print:hidden"></div>

    <div class="lg:ml-64 min-h-screen flex flex-col print:ml-0">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-10 flex items-center gap-3 bg-yellow-400 px-4 py-3 shadow-sm sm:px-6 print:hidden">
            <button type="button" onclick="toggleSidebar()" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/60 text-lg text-stone-900 hover:bg-white lg:hidden" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <form action="{{ route('katalog.index') }}" method="GET" class="relative min-w-0 max-w-xl flex-1">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-500"></i>
                <input type="text" name="q" placeholder="Cari buku di katalog..."
                       class="w-full rounded-xl bg-white/90 py-2.5 pl-10 pr-4 text-sm text-stone-800 placeholder-stone-400 shadow-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900">
            </form>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                <a href="{{ route('home') }}" class="hidden items-center gap-1.5 rounded-xl bg-white/60 px-3 py-2 text-xs font-semibold text-stone-800 hover:bg-white md:inline-flex">
                    <i class="bi bi-globe2"></i> Situs Publik
                </a>
                <div class="flex items-center gap-3 rounded-2xl bg-white/60 px-3 py-1.5 shadow-sm">
                    <div class="hidden text-right leading-tight sm:block">
                        <p class="max-w-[10rem] truncate text-xs font-bold text-stone-900">{{ $user->nama }}</p>
                        <p class="text-[10px] font-medium text-stone-600">{{ $labelRole }}</p>
                    </div>
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-stone-900 text-xs font-bold text-yellow-400">{{ $inisial }}</div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            <x-flash />
            @yield('content')
        </main>

        <footer class="border-t border-stone-200 bg-white py-4 text-center text-xs text-stone-400 print:hidden">
            &copy; {{ date('Y') }} Perpustakaan Politeknik Negeri Lhokseumawe
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
