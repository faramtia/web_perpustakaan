@extends('layouts.app')
@section('title', 'Tipe Koleksi')
@section('page-title', 'Tipe Koleksi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">Nama Tipe</th>
                        <th class="px-5 py-3 font-medium">Jumlah Buku</th>
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-5 py-3">{{ $item->nama_tipe }}</td>
                            <td class="px-5 py-3">{{ $item->buku_count }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.tipe-koleksi.destroy', $item) }}" onsubmit="return confirm('Hapus tipe ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400 italic">Belum ada tipe koleksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border rounded-xl p-5 h-fit">
            <h3 class="font-semibold text-gray-700 mb-3">Tambah Tipe Koleksi</h3>
            <form method="POST" action="{{ route('admin.tipe-koleksi.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="nama_tipe" required placeholder="mis. Buku Cetak, E-Book"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                <button class="w-full bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold py-2.5 rounded-lg">Simpan</button>
            </form>
        </div>
    </div>
@endsection
