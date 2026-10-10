@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Stok Buku" :value="$totalBuku" icon="📖" />
        <x-stat-card label="Peminjaman Aktif" :value="$peminjamanAktif" icon="🔄" />
        <x-stat-card label="Reservasi Menunggu" :value="$reservasiMenunggu" icon="⏳" />
        <x-stat-card label="Kunjungan Hari Ini" :value="$kunjunganHariIni" icon="🧾" />
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="font-semibold text-gray-700">Peminjaman Terbaru</h2>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-sm text-gold-700 hover:underline">Lihat semua</a>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Peminjam</th>
                    <th class="px-5 py-3 font-medium">Buku</th>
                    <th class="px-5 py-3 font-medium">Tanggal Pinjam</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($peminjamanTerbaru as $p)
                    <tr>
                        <td class="px-5 py-3">{{ $p->user->name }} <span class="text-xs text-gray-400 capitalize">({{ $p->user->role }})</span></td>
                        <td class="px-5 py-3">{{ $p->detail->pluck('buku.judul')->join(', ') }}</td>
                        <td class="px-5 py-3">{{ optional($p->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$p->status_tampil" /></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400 italic">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
