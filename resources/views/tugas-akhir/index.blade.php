@extends('layouts.app')
@section('title', 'Review Tugas Akhir')
@section('page-title', 'Review Tugas Akhir')

@section('content')

    {{-- Header Modul --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold leading-tight text-stone-900">Review Tugas Akhir</h1>
                <p class="text-sm text-stone-500">Periksa, validasi, dan setujui usulan tugas akhir yang diunggah anggota.</p>
            </div>
        </div>
<<<<<<< HEAD
=======
    @endif

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Mahasiswa/Dosen</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Judul</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Catatan</th>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($tugasAkhir as $ta)
                    <tr>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">{{ $ta->user->name }}</td>
                        @endif
                        <td class="px-5 py-3">{{ $ta->judul }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$ta->status" /></td>
                        <td class="px-5 py-3 text-gray-500">{{ $ta->catatan_reviewer ?? '-' }}</td>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">
                                @if ($ta->status === 'Menunggu')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('petugas.tugas-akhir.review', $ta) }}">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="Disetujui">
                                            <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('petugas.tugas-akhir.review', $ta) }}">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="Ditolak">
                                            <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400 italic">Belum ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    </div>

    {{-- Kartu Ringkasan Status --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-3xl p-5 border border-stone-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Menunggu Review</p>
                <h3 class="text-2xl font-extrabold text-amber-600">2</h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 grid place-items-center text-xl font-bold">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-stone-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Disetujui</p>
                <h3 class="text-2xl font-extrabold text-emerald-600">14</h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 grid place-items-center text-xl font-bold">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-stone-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Ditolak / Revisi</p>
                <h3 class="text-2xl font-extrabold text-rose-600">1</h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 grid place-items-center text-xl font-bold">
                <i class="bi bi-x-circle-fill"></i>
            </div>
        </div>
    </div>

    {{-- Container Tabel Utama --}}
    <div class="bg-white rounded-3xl border border-stone-100 shadow-sm overflow-hidden">
        
        {{-- Area Filter & Pencarian --}}
        <div class="p-6 pb-4 border-b border-stone-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" placeholder="Cari nama atau judul TA..." 
                    class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs font-medium text-stone-800 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <select class="px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs font-bold text-stone-700 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-100 text-[11px] font-extrabold text-stone-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Mahasiswa / Dosen</th>
                        <th class="py-4 px-6">Judul Tugas Akhir</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Pembimbing</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs font-medium text-stone-700">
                    
                    {{-- Row 1: Disetujui --}}
                    <tr class="hover:bg-stone-50/50 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-800 grid place-items-center font-bold text-xs flex-shrink-0">
                                    NW
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">Nanda Wijaya</p>
                                    <p class="text-[10px] text-stone-400">NIM: 2023573010</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 max-w-xs">
                            <p class="font-bold text-stone-800 mb-1 leading-snug">Perancangan Aplikasi Mobile Library Berbasis Android</p>
                            <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-yellow-700 hover:underline">
                                <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i> Lihat PDF
                            </a>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block">
                                Disetujui
                            </span>
                        </td>
                        <td class="py-4 px-6 text-stone-400">-</td>
                        <td class="py-4 px-6 text-right">
                            <span class="text-stone-300 text-xs">-</span>
                        </td>
                    </tr>

                    {{-- Row 2: Disetujui --}}
                    <tr class="hover:bg-stone-50/50 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-800 grid place-items-center font-bold text-xs flex-shrink-0">
                                    YP
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">Yoga Prasetyo</p>
                                    <p class="text-[10px] text-stone-400">NIM: 2023573012</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 max-w-xs">
                            <p class="font-bold text-stone-800 mb-1 leading-snug">Analisis Efektivitas Layanan Perpustakaan Digital</p>
                            <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-yellow-700 hover:underline">
                                <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i> Lihat PDF
                            </a>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block">
                                Disetujui
                            </span>
                        </td>
                        <td class="py-4 px-6 text-stone-400">-</td>
                        <td class="py-4 px-6 text-right">
                            <span class="text-stone-300 text-xs">-</span>
                        </td>
                    </tr>

                    {{-- Row 3: Menunggu (Butuh Aksi) --}}
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 grid place-items-center font-bold text-xs flex-shrink-0">
                                    PA
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">Putri Amelia</p>
                                    <p class="text-[10px] text-stone-400">NIM: 2023573024</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 max-w-xs">
                            <p class="font-bold text-stone-800 mb-1 leading-snug">Sistem Pendukung Keputusan Pemilihan Buku Referensi</p>
                            <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-yellow-700 hover:underline">
                                <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i> Lihat PDF
                            </a>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 inline-block">
                                Menunggu Review
                            </span>
                        </td>
                        <td class="py-4 px-6 text-stone-400">-</td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                                <button class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition border border-rose-200 flex items-center gap-1">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 4: Menunggu (Butuh Aksi) --}}
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 grid place-items-center font-bold text-xs flex-shrink-0">
                                    RA
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">Rafi Akbar</p>
                                    <p class="text-[10px] text-stone-400">NIM: 2023573033</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 max-w-xs">
                            <p class="font-bold text-stone-800 mb-1 leading-snug">Pemanfaatan Teknologi Informasi dalam Pengelolaan Perpustakaan</p>
                            <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-yellow-700 hover:underline">
                                <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i> Lihat PDF
                            </a>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 inline-block">
                                Menunggu Review
                            </span>
                        </td>
                        <td class="py-4 px-6 text-stone-400">-</td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                                <button class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition border border-rose-200 flex items-center gap-1">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

@endsection