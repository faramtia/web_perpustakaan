@extends('layouts.guest')
@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul/penulis..."
                   class="border rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-gold-400">
            <button class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg">Cari</button>
        </form>

        @auth
            @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                <a href="{{ route('admin.buku.create') }}" class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-medium px-4 py-2 rounded-lg text-center">
                    + Tambah Buku
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Judul</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Tipe</th>
                    <th class="px-5 py-3 font-medium">Stok</th>
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'petugas', 'mahasiswa', 'dosen']))
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($buku as $item)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-700">{{ $item->judul }}</p>
                            <p class="text-xs text-gray-400">{{ $item->penulis }}</p>
                        </td>
                        <td class="px-5 py-3">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-5 py-3">{{ $item->tipeKoleksi->nama_tipe ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ $item->stok > 0 ? 'text-emerald-600' : 'text-red-500' }} font-semibold">
                                {{ $item->stok }}
                            </span>
                        </td>
                        @auth
                            <td class="px-5 py-3">
                                @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.buku.edit', $item) }}" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg">Edit</a>
                                        <form method="POST" action="{{ route('admin.buku.destroy', $item) }}" onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">Hapus</button>
                                        </form>
                                    </div>
                                @elseif (in_array(auth()->user()->role, ['mahasiswa', 'dosen']))
                                    @if ($item->tersedia())
                                        <a href="{{ route('anggota.peminjaman.create') }}" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Pinjam</a>
                                    @else
                                        <form method="POST" action="{{ route('anggota.reservasi.store') }}">
                                            @csrf
                                            <input type="hidden" name="buku_id" value="{{ $item->id }}">
                                            <button class="text-xs bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded-lg">Reservasi</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        @endauth
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400 italic">Belum ada buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $buku->links() }}</div>

@endsection
