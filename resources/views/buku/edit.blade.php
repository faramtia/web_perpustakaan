@extends('layouts.app')
@section('title', 'Edit Buku')

@section('content')
    <x-page-header icon="bi-pencil-square" title="Edit Buku" subtitle="Ubah data buku, lalu simpan." />

    <form method="POST" action="{{ route('admin.buku.update', $buku) }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        @method('PUT')
        @include('buku._form')

        <div class="flex flex-wrap items-center gap-3 border-t border-stone-100 pt-4">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
            <a href="{{ route('admin.buku.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection
