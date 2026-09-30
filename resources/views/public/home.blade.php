@extends('layouts.guest')

@section('title', 'Beranda - Perpustakaan Politeknik Negeri Lhokseumawe')

@section('content')

    <div class="bg-white rounded-2xl border p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Jelajahi Koleksi Kami</h1>
            <p class="text-sm text-gray-500 mt-1">
                Temukan <span class="font-semibold text-gold-700">{{ number_format($totalKoleksi, 0, ',', '.') }}</span> koleksi
                dari <span class="font-semibold text-gold-700">{{ number_format($totalJudul, 0, ',', '.') }}</span> judul
            </p>
        </div>

        <form action="{{ route('katalog.index') }}" method="GET" class="flex w-full lg:w-auto gap-2">
            <input
                type="text"
                name="q"
                placeholder="Judul, penulis, penerbit..."
                class="w-full lg:w-80 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400"
            >
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg whitespace-nowrap">
                🔍 Cari
            </button>
        </form>
    </div>

    <div class="relative overflow-hidden rounded-2xl mb-8 bg-gradient-to-r from-gray-900 via-gray-800 to-gold-700 text-white p-6 sm:p-8">
        <div class="relative z-10 max-w-xl">
            <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-10 w-10 object-contain mb-3 bg-white rounded-full p-1">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gold-400">Selamat Datang di Open Library PNL</h2>
            <p class="mt-2 text-sm sm:text-base text-gray-200">
                Akses katalog, e-jurnal, dan layanan perpustakaan kapan saja. Jangan lewatkan
                <span class="font-semibold">Event Semester</span> yang akan datang!
            </p>
            <a href="{{ route('event.index') }}" class="inline-block mt-4 bg-gold-500 hover:bg-gold-600 text-gray-900 text-sm font-semibold px-5 py-2.5 rounded-full">
                Lihat Event Semester →
            </a>
        </div>
        <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/5"></div>
        <div class="absolute right-16 bottom-[-40px] w-40 h-40 rounded-full bg-white/5"></div>
    </div>

    <div id="layanan" class="mb-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-gold-500 inline-block rounded"></span>
            Quick Access Menu
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('katalog.index') }}" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">📖</div><div class="text-xs font-medium text-gray-600">Katalog Buku</div>
            </a>
            <a href="{{ route('ejurnal.index') }}" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">📰</div><div class="text-xs font-medium text-gray-600">E-Jurnal</div>
            </a>
            <a href="{{ route('katalog.index') }}?tipe=e-tga" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">🎓</div><div class="text-xs font-medium text-gray-600">E-TGA</div>
            </a>
            <a href="{{ route('event.index') }}" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">📅</div><div class="text-xs font-medium text-gray-600">Event Semester</div>
            </a>
            <a href="{{ auth()->check() ? '#' : route('login') }}" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">💬</div><div class="text-xs font-medium text-gray-600">Tanya Pustakawan</div>
            </a>
            <a href="{{ route('artikel.index') }}" class="bg-white border rounded-xl p-4 text-center hover:border-gold-400 hover:shadow-sm transition">
                <div class="text-2xl mb-2">📝</div><div class="text-xs font-medium text-gray-600">Artikel & Blog</div>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

        <div class="bg-white border rounded-xl p-5">
            <h3 class="font-semibold text-gray-700 mb-3">🔥 Koleksi Populer</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                @forelse ($koleksiPopuler as $buku)
                    <li class="flex justify-between border-b pb-2">
                        <span>{{ $buku->judul }}</span>
                        <span class="text-gray-400">{{ $buku->detail_peminjaman_count }}x dipinjam</span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">Belum ada data peminjaman.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <h3 class="font-semibold text-gray-700 mb-3">✨ Koleksi Baru</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                @forelse ($koleksiBaru as $buku)
                    <li class="flex justify-between border-b pb-2">
                        <span>{{ $buku->judul }}</span>
                        <span class="text-gray-400">{{ $buku->created_at->format('d M Y') }}</span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">Belum ada koleksi baru.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div id="artikel" class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-gold-500 inline-block rounded"></span>
            Artikel & Berita Terbaru
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @forelse ($artikel as $item)
                <a href="{{ route('artikel.index') }}" class="bg-white border rounded-xl overflow-hidden hover:shadow-sm transition">
                    <div class="h-28 bg-gold-100 flex items-center justify-center text-3xl">📰</div>
                    <div class="p-4">
                        <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ str_replace('_', ' ', $item->kategori) }}</p>
                        <p class="text-sm font-semibold text-gray-700 line-clamp-2">{{ $item->judul }}</p>
                    </div>
                </a>
            @empty
                @foreach (['Book Review', 'TA Exposure', 'Artikel'] as $kategori)
                    <div class="bg-white border rounded-xl overflow-hidden">
                        <div class="h-28 bg-gold-100 flex items-center justify-center text-3xl">📰</div>
                        <div class="p-4">
                            <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ $kategori }}</p>
                            <p class="text-sm text-gray-400 italic">Belum ada artikel dipublikasikan.</p>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>

@endsection
