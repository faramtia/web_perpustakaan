@extends('layouts.app')
@section('title', $judul)

@section('content')
    <div class="print:hidden">
        <a href="{{ route('petugas.laporan.index') }}" class="mb-3 inline-flex items-center gap-1 text-sm font-semibold text-yellow-700 hover:underline"><i class="bi bi-arrow-left"></i> Semua laporan</a>
    </div>

    <x-page-header :icon="$ikon" :title="$judul" :subtitle="$deskripsi">
        <button type="button" onclick="window.print()" class="btn btn-soft print:hidden"><i class="bi bi-printer"></i> Cetak</button>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-dark print:hidden"><i class="bi bi-download"></i> Unduh CSV</a>
    </x-page-header>

    @if ($filter === 'tanggal')
        <form method="GET" class="card mb-5 flex flex-wrap items-end gap-3 p-4 print:hidden">
            <div><label class="label">Dari</label><input type="date" name="dari" value="{{ request('dari') }}" class="input"></div>
            <div><label class="label">Sampai</label><input type="date" name="sampai" value="{{ request('sampai') }}" class="input"></div>
            <button class="btn btn-primary">Terapkan</button>
            <a href="{{ route('petugas.laporan.show', $jenis) }}" class="btn btn-soft">Reset</a>
        </form>
    @elseif ($filter === 'cari')
        <form method="GET" class="card mb-5 flex flex-wrap items-end gap-3 p-4 print:hidden">
            <div class="min-w-[220px] flex-1"><label class="label">Cari judul</label><input type="text" name="q" value="{{ request('q') }}" class="input"></div>
            <button class="btn btn-primary">Terapkan</button>
        </form>
    @elseif ($filter === 'status-denda')
        <div class="print:hidden"><x-status-tabs :options="['Belum Lunas' => 'Belum Lunas', 'Lunas' => 'Lunas']" /></div>
    @endif

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($ringkasan as [$label, $nilai])
            <div class="card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-400">{{ $label }}</p>
                <p class="text-2xl font-extrabold text-stone-900">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    <div class="table-wrap">
        <table class="tbl">
            <thead><tr><th>No</th>@foreach ($kolom as $k)<th>{{ $k }}</th>@endforeach</tr></thead>
            <tbody>
                @forelse ($baris as $i => $row)
                    <tr><td class="text-stone-400">{{ $i + 1 }}</td>@foreach ($row as $sel)<td>{{ $sel }}</td>@endforeach</tr>
                @empty
                    <x-empty-state :cols="count($kolom) + 1" icon="bi-clipboard-x" title="Tidak ada data untuk filter ini" />
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($catatan)<p class="mt-3 text-xs text-stone-400">{{ $catatan }}</p>@endif
    <p class="mt-1 text-xs text-stone-400">Dicetak {{ now()->translatedFormat('d F Y, H.i') }} WIB oleh {{ auth()->user()->nama }}.</p>
@endsection
