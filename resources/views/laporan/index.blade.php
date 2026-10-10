@extends('layouts.app')
@section('title', 'Gallery Perpustakaan')
@section('page-title', 'Gallery Perpustakaan')

@section('content')

    {{-- Header Gallery --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
            <i class="bi bi-images"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight text-stone-900">Gallery Perpustakaan</h1>
            <p class="text-sm text-stone-500">Dokumentasi kegiatan, fasilitas, dan suasana perpustakaan.</p>
        </div>
    </div>

    {{-- Notifikasi Berhasil (Jika Ada) --}}
    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl px-5 py-3.5 mb-6 text-sm flex items-center gap-3 shadow-sm">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Form Unggah Foto (Lebih Modern & Elegan) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm sticky top-24">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-stone-100">
                    <i class="bi bi-cloud-arrow-up-fill text-yellow-600 text-lg"></i>
                    <h2 class="text-base font-extrabold text-stone-800">Unggah Foto Baru</h2>
                </div>

                <form action="{{ url('/gallery/store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-stone-600 mb-1.5">Judul / Keterangan Foto</label>
                        <input type="text" name="title" placeholder="Contoh: Suasana Ruang Baca Lt. 2" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 mb-1.5">Pilih File Foto</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-stone-200 border-dashed rounded-2xl cursor-pointer bg-stone-50 hover:bg-yellow-50/40 hover:border-yellow-400 transition group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                <i class="bi bi-image text-2xl text-stone-400 group-hover:text-yellow-600 mb-1 transition"></i>
                                <p class="text-xs font-bold text-stone-600 group-hover:text-stone-900">Klik untuk pilih foto</p>
                                <p class="text-[10px] text-stone-400 mt-0.5">PNG, JPG, WEBP (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="photo" class="hidden" required />
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-stone-900 font-extrabold px-6 py-3 rounded-2xl shadow-md transition transform active:scale-95 flex items-center justify-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span>Unggah Foto</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Grid Daftar Foto Gallery --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-stone-100">
                    <h2 class="text-base font-extrabold text-stone-800">Dokumentasi Galeri</h2>
                    <span class="text-xs text-stone-400 font-medium">Total: 1 Foto</span>
                </div>

                {{-- Grid Foto --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    
                    {{-- Item Foto (Bisa di-looping menggunakan @foreach jika datanya dari database) --}}
                    <div class="bg-stone-50 border border-stone-100 rounded-2xl overflow-hidden group shadow-sm hover:shadow-md transition">
                        <div class="relative overflow-hidden aspect-video bg-stone-200">
                            {{-- Gambar Galeri --}}
                            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600&auto=format&fit=crop" 
                                alt="Perpus" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            {{-- Badge Tanggal / Waktu --}}
                            <span class="absolute top-3 left-3 bg-stone-900/70 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                <i class="bi bi-calendar-event mr-1 text-yellow-400"></i> 10 Oct 2026
                            </span>
                        </div>

                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-stone-800 truncate">Perpus PNL</h4>
                                <p class="text-[11px] text-stone-400">Dokumentasi Kegiatan</p>
                            </div>

                            {{-- Tombol Hapus --}}
                            <form action="#" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 grid place-items-center transition shadow-sm" title="Hapus Foto">
                                    <i class="bi bi-trash-fill text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Contoh Item Kosong / Placeholder Jika Ingin Tambah Data Lain --}}
                    <!-- Tambahkan card serupa di dalam foreach controller Anda -->

                </div>
            </div>
        </div>

    </div>

@endsection