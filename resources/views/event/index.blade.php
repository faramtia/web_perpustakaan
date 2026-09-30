@extends('layouts.app')
@section('title', 'Event Semester')
@section('page-title', 'Event Semester')

@section('content')

    @auth
        @if (auth()->user()->role === 'admin')
            <div class="bg-white border rounded-xl p-5 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Buat Event Baru</h3>
                <form method="POST" action="{{ route('admin.event.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @csrf
                    <input type="text" name="judul" required placeholder="Judul event"
                           class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 sm:col-span-2">
                    <input type="date" name="tanggal_mulai" class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <input type="date" name="tanggal_selesai" class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <input type="text" name="lokasi" placeholder="Lokasi" class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <input type="number" name="kuota" placeholder="Kuota (0 = tanpa batas)" required class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <textarea name="deskripsi" placeholder="Deskripsi" rows="2" class="border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 sm:col-span-2"></textarea>
                    <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg sm:col-span-2">
                        Simpan Event
                    </button>
                </form>
            </div>
        @endif
    @endauth

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($event as $e)
            <div class="bg-white border rounded-xl p-5">
                <p class="text-xs text-gray-400 mb-1">
                    {{ optional($e->tanggal_mulai)->format('d M Y') }}
                    @if ($e->tanggal_selesai) &ndash; {{ $e->tanggal_selesai->format('d M Y') }} @endif
                </p>
                <h3 class="font-semibold text-gray-800 mb-1">{{ $e->judul }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $e->deskripsi }}</p>
                <p class="text-xs text-gray-400 mb-3">📍 {{ $e->lokasi ?? '-' }} &middot; {{ $e->peserta_count }}/{{ $e->kuota ?: '∞' }} peserta</p>

                @auth
                    @if (in_array(auth()->user()->role, ['mahasiswa', 'dosen']))
                        <form method="POST" action="{{ route('anggota.event.daftar', $e) }}">
                            @csrf
                            <button class="w-full bg-gold-600 hover:bg-gold-700 text-white text-xs font-semibold py-2 rounded-lg">
                                Daftar Event
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block text-center w-full bg-gray-100 text-gray-500 text-xs font-semibold py-2 rounded-lg">
                        Masuk untuk daftar
                    </a>
                @endauth
            </div>
        @empty
            <p class="text-gray-400 italic col-span-full text-center py-10">Belum ada event.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $event->links() }}</div>

@endsection
