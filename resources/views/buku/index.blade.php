@extends('layouts.app')
@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')

    {{-- Judul halaman --}}
    <div class="mb-5">
        <h1 class="text-2xl font-extrabold">Data Buku</h1>
        <p class="text-sm text-gray-500">Kelola koleksi buku perpustakaan.</p>
    </div>

    {{-- Pencarian & tombol tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul/penulis..."
                       class="bg-white border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-sm w-72
                              focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
            </div>
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                Cari
            </button>
        </form>

        @auth
            @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                <a href="{{ route('admin.buku.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                    <i class="bi bi-plus-lg"></i> Tambah Buku
                </a>
            @endif
        @endauth
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-yellow-50 text-gray-600 text-left">
                <tr>
                    <th class="px-5 py-3 font-semibold">Judul</th>
                    <th class="px-5 py-3 font-semibold">Kategori</th>
                    <th class="px-5 py-3 font-semibold">Tipe</th>
                    <th class="px-5 py-3 font-semibold">Stok</th>
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'petugas', 'mahasiswa', 'dosen']))
                            <th class="px-5 py-3 font-semibold">Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($buku as $item)
                    <tr class="hover:bg-yellow-50/40">
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-800">{{ $item->judul }}</p>
                            <p class="text-xs text-gray-400">{{ $item->penulis }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-yellow-100 text-yellow-700">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $item->tipeKoleksi->nama_tipe ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-block min-w-8 text-center px-2.5 py-1 rounded-md text-xs font-bold
                                         {{ $item->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ (int) $item->stok }}
                            </span>
                        </td>
                        @auth
                            <td class="px-5 py-3">
                                @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.buku.edit', $item) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.buku.destroy', $item) }}" onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf @method('DELETE')
                                            <button class="inline-flex items-center gap-1 text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @elseif (in_array(auth()->user()->role, ['mahasiswa', 'dosen']))
                                    @if ($item->tersedia())
                                        <a href="{{ route('anggota.peminjaman.create') }}"
                                           class="text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Pinjam</a>
                                    @else
                                        <form method="POST" action="{{ route('anggota.reservasi.store') }}">
                                            @csrf
                                            <input type="hidden" name="buku_id" value="{{ $item->id }}">
                                            <button class="text-xs font-medium bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded-lg">Reservasi</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        @endauth
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 italic">Belum ada buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $buku->links() }}</div>

@endsection