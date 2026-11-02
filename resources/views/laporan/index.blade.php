@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
@php
    $kartu = [
        ['peminjaman', 'Laporan Peminjaman', 'Riwayat transaksi peminjaman buku, dapat difilter tanggal.', 'bi-box-arrow-up-right'],
        ['pengunjung', 'Laporan Pengunjung', 'Kehadiran anggota dari data absensi.', 'bi-person-lines-fill'],
        ['koleksi', 'Laporan Koleksi Buku', 'Daftar koleksi beserta stok dan lokasi rak.', 'bi-book'],
        ['denda', 'Laporan Denda', 'Denda terlambat, rusak, dan hilang beserta status bayarnya.', 'bi-coin'],
    ];
@endphp

    <x-page-header icon="bi-bar-chart" title="Laporan" subtitle="Pilih laporan untuk dilihat, dicetak, atau diunduh sebagai CSV." />

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @foreach ($kartu as [$kode, $judul, $deskripsi, $ikon])
            <a href="{{ route('petugas.laporan.show', $kode) }}" class="card flex items-center gap-4 p-5 transition hover:ring-yellow-400 hover:shadow-md">
                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-yellow-100 text-2xl text-yellow-700"><i class="bi {{ $ikon }}"></i></div>
                <div class="min-w-0 flex-1">
                    <h2 class="font-extrabold text-stone-900">{{ $judul }}</h2>
                    <p class="text-sm text-stone-500">{{ $deskripsi }}</p>
                    <p class="mt-1 text-xs font-semibold text-yellow-700">{{ number_format($jumlah[$kode]) }} data</p>
                </div>
                <i class="bi bi-chevron-right text-stone-300"></i>
            </a>
        @endforeach
    </div>
@endsection
