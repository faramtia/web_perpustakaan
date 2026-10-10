@extends('layouts.app')
@section('title', 'Gallery')
@section('page-title', 'Gallery')

@section('content')

    @php
        $bisaKelola = auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas'], true);
    @endphp

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-yellow-400 flex items-center justify-center text-2xl text-white">
            <i class="bi bi-images"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold">Gallery</h1>
            <p class="text-sm text-gray-500">Dokumentasi kegiatan dan suasana perpustakaan.</p>
        </div>
    </div>

    {{-- Pesan --}}
    @if (session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form upload (admin & petugas) --}}
    @if ($bisaKelola)
        <form method="POST" action="{{ route('petugas.gallery.store') }}" enctype="multipart/form-data"
              class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row sm:items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-600 mb-1">Unggah foto (boleh lebih dari satu)</label>
                <input type="file" name="foto[]" accept="image/*" multiple required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white
                              file:mr-3 file:border-0 file:bg-yellow-100 file:text-yellow-700 file:px-3 file:py-1.5 file:rounded-md">
                <p class="text-xs text-gray-400 mt-1">Format jpg, png, gif, webp. Maksimal 2 MB per foto, 10 foto sekali unggah.</p>
            </div>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                <i class="bi bi-upload"></i> Unggah
            </button>
        </form>
    @endif

    {{-- Daftar foto --}}
    @if ($foto->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-2xl py-14 text-center text-gray-400">
            <i class="bi bi-image text-4xl"></i>
            <p class="mt-2 italic">Belum ada foto di gallery.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($foto as $f)
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <a href="{{ $f['url'] }}" target="_blank">
                        <img src="{{ $f['url'] }}" alt="{{ $f['judul'] }}" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4 flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $f['judul'] }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($f['waktu'])->format('d M Y') }}</p>
                        </div>

                        @if ($bisaKelola)
                            <form method="POST" action="{{ route('petugas.gallery.destroy') }}"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="nama" value="{{ $f['nama'] }}">
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection