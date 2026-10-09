@extends('layouts.app')
@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel')

@section('content')

    <form method="POST" action="{{ route('admin.artikel.store') }}" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Judul</label>
            <input type="text" name="judul" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
            <select name="kategori_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(old('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Konten</label>
            <textarea name="isi_artikel" rows="8" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400"></textarea>
        </div>

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Publikasikan
        </button>
    </form>

@endsection
