@extends('layouts.guest')
@section('title', 'Artikel & Blog')
@section('page-title', 'Artikel & Blog')

@section('content')

    @auth
        @if (in_array(auth()->user()->role, ['admin', 'petugas']))
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.artikel.create') }}" class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                    + Tulis Artikel
                </a>
            </div>
        @endif
    @endauth

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($artikel as $a)
            <div class="bg-white border rounded-xl overflow-hidden">
                <div class="h-32 bg-gold-100 flex items-center justify-center text-4xl">📰</div>
                <div class="p-4">
                    <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ $a->kategori->nama_kategori ?? 'Artikel' }}</p>
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $a->judul }}</h3>
                    <p class="text-xs text-gray-400 mb-2">Oleh {{ $a->penulis->name ?? '-' }} &middot; {{ optional($a->tanggal_upload)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $a->isi_artikel }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-400 italic col-span-full text-center py-10">Belum ada artikel dipublikasikan.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $artikel->links() }}</div>

@endsection
