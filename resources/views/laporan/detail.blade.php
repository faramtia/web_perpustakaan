@extends('layouts.app')
@section('title', $judul)

@section('content')
@php
    $ada = fn ($k) => ! empty($filter[$k]);
    $adaFilter = request()->hasAny(['q', 'dari', 'sampai', 'status', 'jenis', 'kategori', 'stok']);
@endphp

    <div class="print:hidden">
        <a href="{{ route('petugas.laporan.index') }}" class="mb-3 inline-flex items-center gap-1 text-sm font-semibold text-yellow-700 hover:underline"><i class="bi bi-arrow-left"></i> Semua laporan</a>
    </div>

    <div class="print:hidden">
        <x-page-header :icon="$ikon" :title="$judul" :subtitle="$deskripsi">
            <button type="button" onclick="window.print()" class="btn btn-soft"><i class="bi bi-printer"></i> Cetak</button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-dark"><i class="bi bi-download"></i> Unduh CSV</a>
        </x-page-header>
    </div>

    {{-- Penyaring: pencarian + rentang tanggal + status + pilihan lain (sesuai laporan) --}}
    <form method="GET" class="card mb-5 grid grid-cols-1 gap-3 p-4 print:hidden sm:grid-cols-2 lg:grid-cols-6">
        @if ($ada('q'))
            <div class="relative sm:col-span-2 lg:col-span-3">
                <label class="label">Cari</label>
                <i class="bi bi-search absolute bottom-3 left-3.5 text-stone-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $filter['q'] }}" class="input pl-10">
            </div>
        @endif

        @if ($ada('tanggal'))
            <div>
                <label class="label">Dari <span class="font-normal text-stone-400">({{ strtolower($filter['tanggal']) }})</span></label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="input">
            </div>
            <div>
                <label class="label">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="input">
            </div>
        @endif

        @if ($ada('status'))
            <div>
                <label class="label">Status</label>
                <select name="status" class="input">
                    <option value="">Semua status</option>
                    @foreach ($filter['status'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('status') === (string) $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @foreach ($filter['select'] ?? [] as $nama => [$semua, $opsi])
            <div>
                <label class="label">{{ ucfirst($nama) }}</label>
                <select name="{{ $nama }}" class="input">
                    <option value="">{{ $semua }}</option>
                    @foreach ($opsi as $v => $l)
                        <option value="{{ $v }}" @selected(request($nama) !== null && request($nama) !== '' && (string) request($nama) === (string) $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="flex items-end gap-2">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
            @if ($adaFilter)<a href="{{ route('petugas.laporan.show', $jenis) }}" class="btn btn-soft">Reset</a>@endif
        </div>
    </form>

    {{-- Kop laporan (tampil di layar dan saat dicetak) --}}
    <div class="card mb-5 p-6 print:rounded-none print:border-0 print:shadow-none print:ring-0 print:p-0">
        <div class="flex items-center gap-4 border-b-2 border-stone-800 pb-4">
            <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-16 w-16 shrink-0 object-contain">
            <div class="flex-1 text-center">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Politeknik Negeri Lhokseumawe</p>
                <h2 class="text-lg font-extrabold uppercase leading-tight text-stone-900 sm:text-xl">UPA. Perpustakaan</h2>
                <p class="text-xs text-stone-500">Jl. Banda Aceh–Medan Km. 280,3, Buketrata, Blang Mangat, Lhokseumawe, Aceh 24301</p>
            </div>
            <div class="hidden w-16 shrink-0 sm:block"></div>
        </div>

        <div class="pt-4 text-center">
            <h3 class="text-base font-extrabold uppercase tracking-wide text-stone-900">{{ $judul }}</h3>
            <p class="text-sm text-stone-600">Periode: {{ $periode }}</p>
            @if ($penyaring)<p class="text-xs text-stone-500">{{ implode(' · ', $penyaring) }}</p>@endif
        </div>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($ringkasan as [$label, $nilai])
            <div class="card p-5 print:p-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-400">{{ $label }}</p>
                <p class="text-2xl font-extrabold text-stone-900">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    <div class="table-wrap print:overflow-visible">
        <table class="tbl print:text-xs">
            <thead><tr><th class="w-12">No</th>@foreach ($kolom as $k)<th>{{ $k }}</th>@endforeach</tr></thead>
            <tbody>
                @forelse ($baris as $i => $row)
                    <tr><td class="text-stone-400">{{ $i + 1 }}</td>@foreach ($row as $sel)<td>{{ $sel }}</td>@endforeach</tr>
                @empty
                    <x-empty-state :cols="count($kolom) + 1" icon="bi-clipboard-x" title="Tidak ada data untuk penyaring ini" />
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($catatan)<p class="mt-3 text-xs text-stone-500">Catatan: {{ $catatan }}</p>@endif

    <div class="mt-8 hidden justify-end print:flex">
        <div class="w-64 text-center text-sm text-stone-800">
            <p>Lhokseumawe, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Perpustakaan</p>
            <div class="h-20"></div>
            <p class="border-t border-stone-800 pt-1">( ............................................ )</p>
        </div>
    </div>

    <p class="mt-3 text-xs text-stone-400">Dicetak {{ now()->translatedFormat('d F Y, H.i') }} WIB oleh {{ auth()->user()->nama }}. Jumlah data: {{ count($baris) }}.</p>
@endsection
