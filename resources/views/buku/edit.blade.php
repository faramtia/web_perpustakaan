@extends('layouts.app')
@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')

@section('content')

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">Edit Buku</h1>
            <p class="text-sm text-stone-500">Ubah data buku, lalu simpan perubahannya.</p>
        </div>
    </div>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="mb-5 max-w-3xl flex items-start gap-2 bg-red-50 ring-1 ring-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <i class="bi bi-exclamation-circle-fill mt-0.5"></i>
            <ul class="space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.buku.update', $buku) }}"
          class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-6 max-w-3xl space-y-4">
        @csrf
        @method('PUT')
        @include('buku._form')

        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-stone-100">
            <button class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-stone-900 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.buku.index') }}"
               class="inline-flex items-center gap-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>

@endsection