@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
    <x-page-header icon="bi-journal-plus" title="Tambah Buku" subtitle="Isi data buku baru." />

    <form method="POST" action="{{ route('admin.buku.store') }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        
        @include('buku._form')

        <div class="flex flex-wrap items-center gap-3 border-t border-stone-100 pt-4">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Buku</button>
            <a href="{{ route('admin.buku.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection
