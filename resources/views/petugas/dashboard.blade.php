@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card label="Menunggu Verifikasi Pinjam" :value="$menungguVerifikasi" icon="✅" />
        <x-stat-card label="Tugas Akhir Menunggu Review" :value="$taMenunggu" icon="🎓" />
        <x-stat-card label="Feedback Baru" :value="$feedbackBaru" icon="💬" />
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="font-semibold text-gray-700">Antrian Verifikasi Peminjaman</h2>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-sm text-gold-700 hover:underline">Lihat semua</a>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Peminjam</th>
                    <th class="px-5 py-3 font-medium">Buku</th>
                    <th class="px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($antrianPeminjaman as $p)
                    <tr>
                        <td class="px-5 py-3">{{ $p->user->name }} <span class="text-xs text-gray-400 capitalize">({{ $p->user->role }})</span></td>
                        <td class="px-5 py-3">{{ $p->detail->pluck('buku.judul')->join(', ') }}</td>
                        <td class="px-5 py-3 flex gap-2">
                            <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                @csrf
                                <input type="hidden" name="keputusan" value="setuju">
                                <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                @csrf
                                <input type="hidden" name="keputusan" value="tolak">
                                <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-6 text-center text-gray-400 italic">Tidak ada antrian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
