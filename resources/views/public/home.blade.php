@extends('layouts.guest')

@section('title', 'Beranda - Perpustakaan Politeknik Negeri Lhokseumawe')

@section('content')

    {{-- Hero + pencarian --}}
    <section class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-stone-900 via-stone-800 to-yellow-700 p-6 text-white sm:p-10">
        <div class="relative z-10 max-w-2xl">
            <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="mb-4 h-12 w-12 rounded-full bg-white object-contain p-1">
            <h1 class="text-2xl font-extrabold text-yellow-400 sm:text-4xl">Selamat Datang di Open Library PNL</h1>
            <p class="mt-2 text-sm text-stone-200 sm:text-base">
                Temukan <span class="font-bold text-yellow-300">{{ number_format($totalKoleksi, 0, ',', '.') }}</span> eksemplar
                dari <span class="font-bold text-yellow-300">{{ number_format($totalJudul, 0, ',', '.') }}</span> judul buku
                dan <span class="font-bold text-yellow-300">{{ number_format($totalTugasAkhir, 0, ',', '.') }}</span> tugas akhir.
            </p>

            <form action="{{ route('katalog.index') }}" method="GET" class="mt-5 flex gap-2">
                <div class="relative flex-1">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400"></i>
                    <input type="text" name="q" placeholder="Judul, penulis, penerbit, ISBN..."
                           class="w-full rounded-xl border-0 bg-white py-3 pl-11 pr-4 text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <button type="submit" class="btn btn-primary px-6">Cari</button>
            </form>
        </div>
        <div class="absolute -right-10 -top-10 h-56 w-56 rounded-full bg-white/5"></div>
        <div class="absolute bottom-[-40px] right-16 h-40 w-40 rounded-full bg-yellow-400/10"></div>
    </section>

    {{-- Akses cepat --}}
    <section id="layanan" class="mb-10">
        <h2 class="mb-4 flex items-center gap-2 text-lg font-extrabold text-stone-800">
            <span class="inline-block h-5 w-1.5 rounded bg-yellow-400"></span> Akses Cepat
        </h2>

        @php
            $akses = [
                ['Katalog Buku', 'bi-book', route('katalog.index')],
                ['Tugas Akhir', 'bi-mortarboard', route('tugas-akhir.index')],
                ['Event Semester', 'bi-calendar-event', route('event.index')],
                ['Tanya Pustakawan', 'bi-chat-dots', auth()->check() && auth()->user()->isAnggota() ? route('anggota.feedback.index') : route('login')],
                ['Artikel & Blog', 'bi-newspaper', route('artikel.index')],
            ];
        @endphp
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($akses as [$label, $ikon, $url])
                <a href="{{ $url }}" class="card p-4 text-center transition hover:-translate-y-0.5 hover:ring-yellow-400 hover:shadow-md">
                    <div class="mx-auto mb-2 grid h-12 w-12 place-items-center rounded-xl bg-yellow-100 text-xl text-yellow-700"><i class="bi {{ $ikon }}"></i></div>
                    <div class="text-xs font-semibold text-stone-700">{{ $label }}</div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Koleksi --}}
    <section class="mb-10 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card p-5">
            <h3 class="mb-3 flex items-center gap-2 font-extrabold text-stone-800"><i class="bi bi-fire text-orange-500"></i> Koleksi Populer</h3>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($koleksiPopuler as $b)
                    <li class="flex items-center justify-between gap-3 py-2.5">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-stone-700">{{ $b->judul }}</p>
                            <p class="truncate text-xs text-stone-400">{{ $b->penulis }}</p>
                        </div>
                        <span class="chip shrink-0 bg-yellow-100 text-yellow-700">{{ $b->detail_peminjaman_count }}× dipinjam</span>
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada data peminjaman.</li>
                @endforelse
            </ul>
        </div>

        <div class="card p-5">
            <h3 class="mb-3 flex items-center gap-2 font-extrabold text-stone-800"><i class="bi bi-stars text-yellow-500"></i> Koleksi Baru</h3>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($koleksiBaru as $b)
                    <li class="flex items-center justify-between gap-3 py-2.5">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-stone-700">{{ $b->judul }}</p>
                            <p class="truncate text-xs text-stone-400">{{ $b->penulis }}</p>
                        </div>
                        <span class="chip shrink-0 bg-stone-100 text-stone-600">{{ $b->kategori->nama_kategori ?? '-' }}</span>
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada koleksi baru.</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- Event mendatang --}}
    <section class="mb-10">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-lg font-extrabold text-stone-800">
                <span class="inline-block h-5 w-1.5 rounded bg-yellow-400"></span> Event Semester Mendatang
            </h2>
            <a href="{{ route('event.index') }}" class="text-sm font-semibold text-yellow-700 hover:underline">Lihat semua →</a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @forelse ($eventMendatang as $e)
                <a href="{{ route('event.show', $e) }}" class="card flex gap-4 p-4 transition hover:ring-yellow-400 hover:shadow-md">
                    <div class="w-14 shrink-0 rounded-xl bg-yellow-100 py-2 text-center text-yellow-800">
                        <p class="text-xl font-extrabold leading-none">{{ $e->tanggal_mulai->format('d') }}</p>
                        <p class="text-xs font-semibold">{{ $e->tanggal_mulai->translatedFormat('M') }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="line-clamp-2 font-bold text-stone-800">{{ $e->judul }}</p>
                        <p class="mt-1 truncate text-xs text-stone-400"><i class="bi bi-geo-alt"></i> {{ $e->lokasi ?: 'Perpustakaan PNL' }}</p>
                    </div>
                </a>
            @empty
                <div class="card col-span-full px-6 py-8 text-center text-sm italic text-stone-400">Belum ada event mendatang.</div>
            @endforelse
        </div>
    </section>

    {{-- Artikel --}}
    <section id="artikel" class="mb-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-lg font-extrabold text-stone-800">
                <span class="inline-block h-5 w-1.5 rounded bg-yellow-400"></span> Artikel &amp; Berita Terbaru
            </h2>
            <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-yellow-700 hover:underline">Lihat semua →</a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @forelse ($artikel as $a)
                <a href="{{ route('artikel.show', $a) }}" class="card overflow-hidden transition hover:ring-yellow-400 hover:shadow-md">
                    <div class="grid h-28 place-items-center bg-gradient-to-br from-yellow-50 to-amber-100 text-4xl text-yellow-600/70"><i class="bi bi-journal-text"></i></div>
                    <div class="p-4">
                        <p class="mb-1 text-xs font-bold uppercase text-yellow-700">{{ $a->kategori->nama_kategori ?? 'Artikel' }}</p>
                        <p class="line-clamp-2 text-sm font-bold text-stone-800">{{ $a->judul }}</p>
                        <p class="mt-1 text-xs text-stone-400">{{ optional($a->tanggal_upload)->translatedFormat('d M Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="card col-span-full px-6 py-8 text-center text-sm italic text-stone-400">Belum ada artikel dipublikasikan.</div>
            @endforelse
        </div>
    </section>

@endsection
