@extends('layouts.app')
@section('title', 'Tulis Artikel')

@section('content')
    <x-page-header icon="bi-pencil-square" title="Tulis Artikel" subtitle="Artikel akan tampil di halaman Artikel & Blog." />

    <form method="POST" action="{{ route('admin.artikel.store') }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        <div><label class="label">Judul</label><input type="text" name="judul" value="{{ old('judul') }}" required maxlength="250" class="input"></div>
        <div>
            <label class="label">Kategori</label>
            <select name="kategori_id" required class="input">
                @foreach ($kategori as $k)<option value="{{ $k->id }}" @selected(old('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
            </select>
        </div>
        <div><label class="label">Isi artikel</label><textarea name="isi_artikel" rows="10" required class="input">{{ old('isi_artikel') }}</textarea></div>
        <div class="flex gap-3">
            <button class="btn btn-primary"><i class="bi bi-send"></i> Publikasikan</button>
            <a href="{{ route('artikel.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection
