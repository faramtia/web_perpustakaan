@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')

@section('content')

    {{-- Banner Selamat Datang Modern --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-stone-900 via-stone-800 to-stone-900 text-white rounded-3xl p-8 mb-8 shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-yellow-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400/20 text-yellow-400 border border-yellow-400/30 inline-block mb-3">
                    <i class="bi bi-shield-check-fill mr-1"></i> Area Petugas Perpustakaan
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Selamat datang, {{ auth()->user()->name ?? 'Dini' }}! 👋</h1>
                <p class="text-stone-300 text-sm max-w-xl">Kelola koleksi buku, pantau transaksi peminjaman, dan berikan pelayanan terbaik dengan sistem perpustakaan yang efisien.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white/10 text-right">
                <p class="text-xs text-stone-300 font-medium"><i class="bi bi-calendar-event mr-1.5 text-yellow-400"></i> Waktu Sistem</p>
                <p class="text-sm font-bold text-white">{{ date('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Statistik Cards Modern --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        
        {{-- Card 1: Total Buku --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-book-fill"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short"></i> +3,2%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Total Koleksi Buku</p>
            <h3 class="text-2xl font-extrabold text-stone-900">12.450</h3>
        </div>

        {{-- Card 2: Peminjaman Aktif --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short"></i> +12%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Peminjaman Aktif</p>
            <h3 class="text-2xl font-extrabold text-stone-900">234</h3>
        </div>

        {{-- Card 3: Pengembalian --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short"></i> +20%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Pengembalian Hari Ini</p>
            <h3 class="text-2xl font-extrabold text-stone-900">48</h3>
        </div>

        {{-- Card 4: Reservasi --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short"></i> +36%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Reservasi Buku</p>
            <h3 class="text-2xl font-extrabold text-stone-900">15</h3>
        </div>

        {{-- Card 5: Denda --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-coin"></i>
                </div>
                <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-down-short"></i> -12%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Total Denda</p>
            <h3 class="text-2xl font-extrabold text-stone-900">Rp 850k</h3>
        </div>

        {{-- Card 6: Pengunjung --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 grid place-items-center text-2xl group-hover:scale-110 transition">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short"></i> +18%
                </span>
            </div>
            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Pengunjung Hari Ini</p>
            <h3 class="text-2xl font-extrabold text-stone-900">320</h3>
        </div>

    </div>

    {{-- Menu Cepat / Quick Actions & Informasi Tambahan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Menu Cepat (2 Kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-stone-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-extrabold text-stone-900">Akses Cepat Menu</h2>
                <span class="text-xs text-stone-400 font-medium">Pintasan navigasi utama</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <a href="#" class="p-4 rounded-2xl border border-stone-100 hover:border-yellow-400 hover:bg-yellow-50/40 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-yellow-100 text-yellow-800 grid place-items-center font-bold">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-stone-800 group-hover:text-stone-900">Kelola Buku</h4>
                            <p class="text-xs text-stone-400">Tambah & edit koleksi</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right text-stone-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition"></i>
                </a>

                <a href="#" class="p-4 rounded-2xl border border-stone-100 hover:border-yellow-400 hover:bg-yellow-50/40 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-800 grid place-items-center font-bold">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-stone-800 group-hover:text-stone-900">Data Peminjaman</h4>
                            <p class="text-xs text-stone-400">Proses transaksi aktif</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right text-stone-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition"></i>
                </a>

                <a href="{{ url('/denda') }}" class="p-4 rounded-2xl border border-stone-100 hover:border-yellow-400 hover:bg-yellow-50/40 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-800 grid place-items-center font-bold">
                            <i class="bi bi-coin"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-stone-800 group-hover:text-stone-900">Modul Denda</h4>
                            <p class="text-xs text-stone-400">Kelola status peminjaman</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right text-stone-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition"></i>
                </a>

                <a href="#" class="p-4 rounded-2xl border border-stone-100 hover:border-yellow-400 hover:bg-yellow-50/40 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-800 grid place-items-center font-bold">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-stone-800 group-hover:text-stone-900">Laporan Bulanan</h4>
                            <p class="text-xs text-stone-400">Rekapitulasi data</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right text-stone-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
        </div>

        {{-- Sidebar Kanan / Informasi Sistem --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-stone-900 mb-4">Aktivitas Sistem</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-8 h-8 rounded-xl bg-yellow-50 text-yellow-600 grid place-items-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div>
                            <p class="font-bold text-stone-800">Sistem Diperbarui</p>
                            <p class="text-stone-400">Modul denda dan tata letak UI telah disesuaikan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 grid place-items-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="font-bold text-stone-800">Koneksi Database Aman</p>
                            <p class="text-stone-400">Sinkronisasi data peminjaman berjalan lancar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-stone-100 text-center">
                <p class="text-[11px] text-stone-400 font-medium">PNL Library Management System v2.4</p>
            </div>
        </div>

    </div>

@endsection