@extends('layouts.app')
@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')

@section('content')

    <form method="POST" action="{{ route('admin.buku.update', $buku) }}" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
        @csrf
        @method('PUT')
        @include('buku._form')

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Simpan Perubahan
        </button>
    </form>

@endsection
