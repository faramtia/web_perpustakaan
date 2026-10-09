@extends('layouts.guest')
@section('title', 'E-Jurnal')
@section('page-title', 'E-Jurnal')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul jurnal..."
                   class="border rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-gold-400">
            <button class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg">Cari</button>
        </form>

        @auth
            @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                <a href="{{ route('admin.ejurnal.create') }}" class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-medium px-4 py-2 rounded-lg text-center">
                    + Tambah E-Jurnal
                </a>
            @endif
        @endauth
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($ejurnal as $j)
            <div class="bg-white border rounded-xl p-5">
                <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ $j->kategori->nama_kategori ?? 'Umum' }}</p>
                <h3 class="font-semibold text-gray-800 mb-1">{{ $j->judul }}</h3>
                <p class="text-xs text-gray-400 mb-2">{{ $j->penulis }} &middot; {{ $j->tahun_terbit }}</p>
                <p class="text-sm text-gray-600 line-clamp-3">{{ $j->abstrak }}</p>
                @if ($j->lokasi_rak)
                    <p class="text-xs text-gray-400 mt-3">📍 Versi lengkap: {{ $j->lokasi_rak }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-400 italic col-span-full text-center py-10">Belum ada e-jurnal.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $ejurnal->links() }}</div>

@endsection
