@extends('layouts.app')
@section('title', 'Absensi')

@section('content')
@php $staff = auth()->user()->isStaff(); @endphp

    <x-page-header icon="bi-person-check" :title="$staff ? 'Absensi Pengunjung' : 'Riwayat Absensi'"
        :subtitle="$staff ? 'Catat dan pantau kehadiran anggota di perpustakaan.' : 'Riwayat kehadiranmu di perpustakaan.'" />

    @if ($staff)
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
            <form method="POST" action="{{ route('petugas.absensi.store') }}" class="card p-5 lg:col-span-2">
                @csrf
                <div class="mb-1 flex items-center justify-between gap-2">
                    <h2 class="font-bold">Catat kehadiran</h2>
                    <a href="{{ route('kiosk.absensi') }}" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-display"></i> Buka Kiosk</a>
                </div>
                <p class="mb-3 text-sm text-stone-500">Masukkan NIM / NIP anggota. Versi awal masih manual; nanti bisa diganti scan QR kartu anggota.</p>
                <div class="flex flex-wrap gap-3">
                    <div class="relative min-w-[220px] flex-1">
                        <i class="bi bi-person-badge absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400"></i>
                        <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" placeholder="NIM / NIP anggota" required autofocus class="input pl-10">
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Catat Absensi</button>
                </div>
            </form>
            <x-stat-card label="Hadir Hari Ini" :value="$hariIni" hint="pengunjung tercatat" icon="bi-people" tone="green" />
        </div>

        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / NIM..." class="input max-w-xs">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="input max-w-[11rem]">
            <button class="btn btn-soft">Saring</button>
            @if (request()->hasAny(['q', 'tanggal']))<a href="{{ route('petugas.absensi.index') }}" class="btn btn-soft">Reset</a>@endif
        </form>
    @endif

    <div class="table-wrap">
        <table class="tbl">
            <thead><tr>@if ($staff)<th>Anggota</th>@endif<th>Tanggal</th><th>Waktu Masuk</th></tr></thead>
            <tbody>
                @forelse ($absensi as $a)
                    <tr>
                        @if ($staff)<td><x-user-cell :user="$a->user" :sub="($a->user->nim_nip ?? '').' · '.ucfirst((string) ($a->user->role ?? ''))" /></td>@endif
                        <td class="whitespace-nowrap">{{ optional($a->tanggal)->translatedFormat('l, d M Y') ?? '-' }}</td>
                        <td class="whitespace-nowrap text-stone-600"><i class="bi bi-clock mr-1 text-stone-400"></i>{{ $a->waktu_masuk ? substr($a->waktu_masuk, 0, 5) : '-' }}</td>
                    </tr>
                @empty
                    <x-empty-state :cols="$staff ? 3 : 2" icon="bi-person-x" title="Belum ada data absensi" text="Kehadiran akan muncul di sini." />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $absensi->links() }}</div>
@endsection
