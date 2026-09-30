@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')

    <form method="POST" action="{{ route('anggota.peminjaman.store') }}" class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Pilih Buku</label>
            <select name="buku_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                <option value="">-- Pilih buku yang tersedia --</option>
                @foreach ($buku as $b)
                    <option value="{{ $b->id }}">{{ $b->judul }} (stok: {{ $b->stok }})</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Hanya menampilkan buku dengan stok tersedia. Kalau tidak ada di daftar, buat reservasi lewat halaman katalog.</p>
        </div>

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Ajukan Peminjaman
        </button>
    </form>

@endsection
