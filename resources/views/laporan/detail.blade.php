@extends('layouts.app')
@section('title', $judul)
@section('page-title', $judul)

@push('head')
<style>
    @media print {
        aside, header, .no-print { display: none !important; }
        .ml-64, .lg\:ml-64 { margin-left: 0 !important; }
    }
</style>
@endpush

@section('content')

    {{-- Judul halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl"><i class="bi {{ $ikon }}"></i></div>
            <div>
                <h1 class="text-2xl font-extrabold leading-tight">{{ $judul }}</h1>
                <p class="text-sm text-stone-500">{{ $deskripsi }}</p>
            </div>
        </div>
        <div class="no-print flex gap-2">
            <a href="{{ route('petugas.laporan.index') }}" class="inline-flex items-center gap-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </div>
    </div>

    {{-- Filter --}}
    @if ($filter)
        <form method="GET" action="{{ url()->current() }}" class="no-print flex flex-wrap items-end gap-3 mb-6">
            @if ($filter === 'tanggal')
                <label class="text-xs font-semibold text-stone-500">Dari
                    <input type="date" name="dari" value="{{ request('dari') }}" class="mt-1 block bg-white border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </label>
                <label class="text-xs font-semibold text-stone-500">Sampai
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="mt-1 block bg-white border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </label>
            @else
                <label class="text-xs font-semibold text-stone-500">Cari judul
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul buku..." class="mt-1 block w-64 bg-white border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </label>
            @endif
            <button class="bg-yellow-400 hover:bg-yellow-500 text-stone-900 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">Terapkan</button>
            <a href="{{ url()->current() }}" class="text-sm font-semibold text-stone-500 hover:text-stone-800 py-2.5">Reset</a>
        </form>
    @endif

    {{-- Ringkasan --}}
    <div class="grid sm:grid-cols-3 gap-3 mb-6">
        @foreach ($ringkasan as [$label, $nilai])
            <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-5">
                <p class="text-xs text-stone-500">{{ $label }}</p>
                <p class="text-2xl font-extrabold mt-1">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-stone-500 text-left border-b border-stone-200">
                <tr>
                    @foreach ($kolom as $k)
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">{{ $k }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($baris as $b)
                    <tr class="hover:bg-stone-50">
                        @foreach ($b as $i => $sel)
                            <td class="px-6 py-3.5 {{ $i === 0 ? 'font-semibold text-stone-800' : 'text-stone-600' }}">{{ $sel }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($kolom) }}" class="px-6 py-14 text-center">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 grid place-items-center text-2xl mb-3"><i class="bi bi-inbox"></i></div>
                            <p class="font-semibold text-stone-700">Tidak ada data</p>
                            <p class="text-sm text-stone-400">Coba ubah filter, atau data belum tersedia.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($catatan)
        <p class="mt-4 text-xs text-stone-400"><i class="bi bi-info-circle"></i> {{ $catatan }}</p>
    @endif

@endsection