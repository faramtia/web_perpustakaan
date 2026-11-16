@extends('layouts.app')
@section('title', 'Tambah E-Publication')
@section('page-title', 'Tambah E-Publication')

@section('content')

    @php
        $isTa = $jenis === 'tugas-akhir';
        $input = 'w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400';
    @endphp

    <div class="flex gap-1 border-b mb-5 text-sm font-medium max-w-2xl">
        <a href="{{ route('admin.epublication.create', ['jenis' => 'jurnal']) }}"
           class="px-4 py-2.5 border-b-2 -mb-px {{ ! $isTa ? 'border-gold-500 text-gold-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">📰 E-Jurnal</a>
        <a href="{{ route('admin.epublication.create', ['jenis' => 'tugas-akhir']) }}"
           class="px-4 py-2.5 border-b-2 -mb-px {{ $isTa ? 'border-gold-500 text-gold-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">🎓 Tugas Akhir</a>
    </div>

    @if (! $isTa)
        <form method="POST" action="{{ route('admin.epublication.jurnal.store') }}" enctype="multipart/form-data" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required class="{{ $input }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1900" max="2100" class="{{ $input }}">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                <select name="kategori_id" required class="{{ $input }}">
                    <option value="">-- pilih kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" @selected(old('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Abstrak</label>
                <textarea name="abstrak" rows="5" required class="{{ $input }}">{{ old('abstrak') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Abstrak tampil untuk semua pengunjung.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">File Jurnal Lengkap (PDF, opsional, maks. 20MB)</label>
                <input type="file" name="file" accept="application/pdf" class="{{ $input }}">
                <p class="text-xs text-gray-400 mt-1">Kalau diisi, anggota yang sudah login bisa membaca versi lengkap. Pengunjung umum hanya melihat abstrak.</p>
            </div>
            <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Simpan E-Jurnal</button>
        </form>
    @else
        <form method="POST" action="{{ route('admin.epublication.tugas-akhir.store') }}" enctype="multipart/form-data"
              class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Penulis (mahasiswa/dosen)</label>
                <select name="user_id" required class="{{ $input }}">
                    <option value="">-- pilih anggota --</option>
                    @foreach ($anggota as $u)
                        <option value="{{ $u->id }}" @selected(old('user_id') == $u->id)>{{ $u->nama }} ({{ $u->nim_nip }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Judul Tugas Akhir</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Pembimbing</label>
                <input type="text" name="pembimbing" value="{{ old('pembimbing') }}" class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">File (PDF, maks. 10MB)</label>
                <input type="file" name="file" accept="application/pdf" required class="{{ $input }}">
            </div>
            <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Simpan Tugas Akhir</button>
        </form>
    @endif

@endsection
