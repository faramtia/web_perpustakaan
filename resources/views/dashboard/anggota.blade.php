@extends('layouts.app')
@section('title', 'Dashboard Saya')

@section('content')
@php
    $user = auth()->user();
    $aturan = $user->jenisUser;
@endphp

    {{-- Sambutan --}}
    <div class="relative mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-stone-900 via-stone-800 to-yellow-700 p-6 text-white sm:p-8">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-stone-300">Selamat datang,</p>
                <h1 class="text-2xl font-extrabold sm:text-3xl">{{ $user->nama }}</h1>
                <p class="mt-2 inline-block rounded-full bg-white/10 px-3 py-1 text-xs capitalize">{{ $user->role }} &middot; {{ $user->nim_nip }}</p>
                @if ($aturan)
                    <p class="mt-3 max-w-xl text-xs text-stone-300">
                        Masa pinjam {{ $aturan->lama_pinjam_hari }} hari. Denda keterlambatan Rp{{ number_format($aturan->denda_per_hari, 0, ',', '.') }} per hari.
                    </p>
                @endif
            </div>
            <a href="{{ route('anggota.peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajukan Peminjaman</a>
        </div>
        <div class="absolute -right-10 -top-10 h-56 w-56 rounded-full bg-white/5"></div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-stat-card label="Pinjaman Aktif" :value="$peminjamanAktif->where('status', 'Dipinjam')->count()" hint="termasuk yang menunggu: {{ $peminjamanAktif->count() }}" icon="bi-book" tone="blue" :href="route('anggota.peminjaman.index')" />
        <x-stat-card label="Denda Belum Lunas" :value="'Rp '.number_format($totalDenda, 0, ',', '.')" hint="bayar di meja petugas" icon="bi-coin" tone="red" :href="route('anggota.denda.index')" />
        <x-stat-card label="Kunjungan Bulan Ini" :value="$kunjunganBulanIni" hint="dari absensi perpustakaan" icon="bi-person-check" tone="green" :href="route('anggota.absensi.index')" />
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <div class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-bold text-stone-800"><i class="bi bi-book mr-1 text-yellow-600"></i> Peminjaman Saya</h3>
                <a href="{{ route('anggota.peminjaman.index') }}" class="text-xs font-semibold text-yellow-700 hover:underline">Lihat semua</a>
            </div>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($peminjamanAktif as $p)
                    <li class="flex items-center justify-between gap-3 py-2.5">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-stone-700">{{ $p->judul_buku }}</p>
                            <p class="text-xs {{ $p->isTerlambat() ? 'font-semibold text-red-600' : 'text-stone-400' }}">
                                @if ($p->tanggal_jatuh_tempo)
                                    Jatuh tempo {{ $p->tanggal_jatuh_tempo->format('d M Y') }}
                                @else
                                    Menunggu verifikasi petugas
                                @endif
                            </p>
                        </div>
                        <x-status-badge :status="$p->status_tampil" />
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada peminjaman aktif.</li>
                @endforelse
            </ul>
        </div>

        <div class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-bold text-stone-800"><i class="bi bi-calendar-check mr-1 text-yellow-600"></i> Reservasi Saya</h3>
                <a href="{{ route('anggota.reservasi.index') }}" class="text-xs font-semibold text-yellow-700 hover:underline">Lihat semua</a>
            </div>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($reservasiSaya as $r)
                    <li class="flex items-center justify-between gap-3 py-2.5">
                        <span class="truncate text-stone-700">{{ $r->buku->judul ?? '-' }}</span>
                        <x-status-badge :status="$r->status" />
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada reservasi.</li>
                @endforelse
            </ul>
        </div>

        <div class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-bold text-stone-800"><i class="bi bi-mortarboard mr-1 text-yellow-600"></i> Tugas Akhir Saya</h3>
                <a href="{{ route('anggota.tugas-akhir.index') }}" class="text-xs font-semibold text-yellow-700 hover:underline">Lihat semua</a>
            </div>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($tugasAkhirSaya as $ta)
                    <li class="flex items-center justify-between gap-3 py-2.5">
                        <span class="truncate text-stone-700">{{ $ta->judul }}</span>
                        <x-status-badge :status="$ta->status" />
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada pengajuan tugas akhir.</li>
                @endforelse
            </ul>
        </div>

        <div class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-bold text-stone-800"><i class="bi bi-calendar-event mr-1 text-yellow-600"></i> Event Mendatang</h3>
                <a href="{{ route('event.index') }}" class="text-xs font-semibold text-yellow-700 hover:underline">Lihat semua</a>
            </div>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($eventMendatang as $e)
                    <li class="flex items-center gap-3 py-2.5">
                        <div class="w-12 shrink-0 rounded-lg bg-yellow-100 py-1.5 text-center text-yellow-800">
                            <p class="text-base font-extrabold leading-none">{{ $e->tanggal_mulai->format('d') }}</p>
                            <p class="text-[10px] font-semibold">{{ $e->tanggal_mulai->translatedFormat('M') }}</p>
                        </div>
                        <a href="{{ route('event.show', $e) }}" class="min-w-0 flex-1 truncate font-semibold text-stone-700 hover:text-yellow-700">{{ $e->judul }}</a>
                        @if (in_array($e->id, $terdaftar))
                            <x-status-badge status="Terdaftar" />
                        @endif
                    </li>
                @empty
                    <li class="py-4 italic text-stone-400">Belum ada event mendatang.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
