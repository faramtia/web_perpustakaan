@extends('layouts.app')
@section('title', 'Tambah E-Jurnal')
@section('page-title', 'Tambah E-Jurnal')

@section('content')

    <form method="POST" action="{{ route('admin.ejurnal.store') }}" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Judul</label>
            <input type="text" name="judul" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Penulis</label>
                <input type="text" name="penulis" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
                <input type="number" name="tahun" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
            <select name="kategori_id" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                <option value="">-</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Abstrak</label>
            <textarea name="abstrak" rows="5" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi Rak (untuk versi lengkap)</label>
            <input type="text" name="lokasi_rak" placeholder="mis. Ruang Terbitan Berkala - Rak 3"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
        </div>

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Simpan E-Jurnal
        </button>
    </form>

@endsection
