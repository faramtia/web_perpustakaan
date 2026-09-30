@extends('layouts.app')
@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')

    <form method="POST" action="{{ route('admin.buku.store') }}" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
        @csrf
        @include('buku._form')

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Simpan Buku
        </button>
    </form>

@endsection
