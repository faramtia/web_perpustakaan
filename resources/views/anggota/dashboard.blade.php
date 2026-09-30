@extends('layouts.app')
@section('title', 'Dashboard Saya')
@section('page-title', 'Dashboard Saya')

@section('content')

    <div class="bg-gradient-to-r from-gray-900 to-gold-700 text-white rounded-xl p-5 mb-6">
        <p class="text-sm text-gray-200">Selamat datang,</p>
        <p class="text-xl font-bold">{{ auth()->user()->name }}</p>
        <p class="text-xs mt-1 inline-block bg-white/10 px-2 py-0.5 rounded-full capitalize">
            {{ auth()->user()->role }} &middot; {{ auth()->user()->nim_nip }}
        </p>

        @if (auth()->user()->role === 'dosen')
            <p class="text-xs text-gray-200 mt-2">Sebagai dosen, kamu juga bisa membimbing pengajuan Tugas Akhir mahasiswa di menu terkait.</p>
        @else
            <p class="text-xs text-gray-200 mt-2">Jangan lupa cek jatuh tempo peminjaman kamu supaya tidak kena denda.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white border rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-700">📚 Peminjaman Aktif</h3>
                <a href="{{ route('anggota.peminjaman.create') }}" class="text-xs bg-gold-600 hover:bg-gold-700 text-white px-3 py-1.5 rounded-lg">+ Ajukan Pinjam</a>
            </div>
            <ul class="space-y-2 text-sm text-gray-600">
                @forelse ($peminjamanAktif as $p)
                    <li class="flex justify-between border-b pb-2">
                        <span>{{ $p->detail->pluck('buku.judul')->join(', ') }}</span>
                        <span class="text-gray-400">jatuh tempo {{ optional($p->tanggal_jatuh_tempo)->format('d M') }}</span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">Belum ada peminjaman aktif.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <h3 class="font-semibold text-gray-700 mb-3">⏳ Reservasi Saya</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                @forelse ($reservasiSaya as $r)
                    <li class="flex justify-between border-b pb-2">
                        <span>{{ $r->buku->judul }}</span>
                        <x-status-badge :status="$r->status" />
                    </li>
                @empty
                    <li class="text-gray-400 italic">Belum ada reservasi.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white border rounded-xl p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-700">🎓 {{ auth()->user()->role === 'dosen' ? 'Tugas Akhir Bimbingan' : 'Tugas Akhir Saya' }}</h3>
                <a href="{{ route('anggota.tugas-akhir.index') }}" class="text-xs text-gold-700 hover:underline">Lihat semua</a>
            </div>
            <ul class="space-y-2 text-sm text-gray-600">
                @forelse ($tugasAkhirSaya as $ta)
                    <li class="flex justify-between border-b pb-2">
                        <span>{{ $ta->judul }}</span>
                        <x-status-badge :status="$ta->status" />
                    </li>
                @empty
                    <li class="text-gray-400 italic">Belum ada pengajuan tugas akhir.</li>
                @endforelse
            </ul>
        </div>
    </div>

@endsection
