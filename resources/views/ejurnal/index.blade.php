@extends('layouts.app')
@section('title', 'E-Jurnal')
@section('page-title', 'E-Jurnal')

@section('content')

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">E-Jurnal</h1>
            <p class="text-sm text-stone-500">Koleksi jurnal dan publikasi ilmiah perpustakaan.</p>
        </div>
    </div>

    {{-- Pencarian & tombol tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <form method="GET" class="flex gap-2">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul jurnal..."
                       class="bg-white border border-stone-200 rounded-xl pl-9 pr-4 py-2.5 text-sm w-72
                              focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
            </div>
            <button class="bg-yellow-400 hover:bg-yellow-500 text-stone-900 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                Cari
            </button>
        </form>

        @auth
            @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                <a href="{{ route('admin.ejurnal.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                    <i class="bi bi-plus-lg"></i> Tambah E-Jurnal
                </a>
            @endif
        @endauth
    </div>

    {{-- Kartu jurnal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($ejurnal as $j)
            <article class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-5 flex flex-col hover:ring-yellow-400 hover:shadow-md transition">

                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-yellow-100 text-yellow-700">
                        {{ $j->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                    @if ($j->tahun)
                        <span class="text-xs font-medium text-stone-400">{{ $j->tahun }}</span>
                    @endif
                </div>

                <h3 class="font-bold text-stone-800 leading-snug mb-1">{{ $j->judul }}</h3>
                <p class="text-xs text-stone-400 mb-3"><i class="bi bi-person"></i> {{ $j->penulis }}</p>

                <p class="text-sm text-stone-600 line-clamp-3">{{ $j->abstrak }}</p>

                @if ($j->lokasi_rak)
                    <div class="mt-auto pt-4">
                        <div class="flex items-center gap-2 border-t border-stone-100 pt-3 text-xs text-stone-500">
                            <i class="bi bi-geo-alt text-yellow-600"></i>
                            <span>Versi lengkap: {{ $j->lokasi_rak }}</span>
                        </div>
                    </div>
                @endif
            </article>
        @empty
            <div class="col-span-full bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm py-14 text-center">
                <div class="mx-auto w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 grid place-items-center text-2xl mb-3">
                    <i class="bi bi-journal-x"></i>
                </div>
                <p class="font-semibold text-stone-700">Belum ada e-jurnal</p>
                <p class="text-sm text-stone-400">Jurnal yang ditambahkan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $ejurnal->links() }}</div>

@endsection