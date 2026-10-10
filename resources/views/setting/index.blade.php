@extends('layouts.app')
@section('title', 'Pengaturan Perpustakaan')
@section('page-title', 'Pengaturan Perpustakaan')

@section('content')

    {{-- Header Setting --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
            <i class="bi bi-gear-fill"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight text-stone-900">Setting Perpustakaan</h1>
            <p class="text-sm text-stone-500">Informasi, konfigurasi layanan, dan aturan peminjaman sistem.</p>
        </div>
    </div>

    {{-- Notifikasi Berhasil --}}
    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl px-5 py-3.5 mb-6 text-sm flex items-center gap-3 shadow-sm">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ url('/setting') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kolom Kiri: Informasi Utama & Aturan Peminjaman --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card 1: Informasi Perpustakaan --}}
                <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b border-stone-100">
                        <i class="bi bi-building text-yellow-600 text-lg"></i>
                        <h2 class="text-base font-extrabold text-stone-800">Informasi Perpustakaan</h2>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Nama Perpustakaan</label>
                            <input type="text" name="nama_perpustakaan" value="{{ old('nama_perpustakaan', 'Perpustakaan Politeknik Negeri Lhokseumawe') }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Email Resmi</label>
                            <input type="email" name="email" value="{{ old('email', 'perpustakaan@pnl.ac.id') }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Nomor Telepon / WA</label>
                            <input type="text" name="telepon" value="{{ old('telepon', '0645-42785') }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Website</label>
                            <input type="text" name="website" value="{{ old('website', 'https://pnl.ac.id') }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-medium">Jl. Banda Aceh - Medan, Buketrata, Lhokseumawe, Aceh</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Aturan Peminjaman & Denda (FITUR BARU) --}}
                <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b border-stone-100">
                        <i class="bi bi-sliders text-yellow-600 text-lg"></i>
                        <h2 class="text-base font-extrabold text-stone-800">Konfigurasi Aturan Peminjaman</h2>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Maks. Peminjaman (Buku)</label>
                            <input type="number" name="max_pinjam" value="{{ old('max_pinjam', 3) }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Lama Pinjam (Hari)</label>
                            <input type="number" name="lama_pinjam" value="{{ old('lama_pinjam', 7) }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Denda Per Hari (Rp)</label>
                            <input type="number" name="denda_per_hari" value="{{ old('denda_per_hari', 1000) }}" 
                                class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white transition font-semibold">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Jam Operasional & Tombol Simpan --}}
            <div class="space-y-6">
                
                {{-- Jam Operasional --}}
                <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b border-stone-100">
                        <i class="bi bi-clock-history text-yellow-600 text-lg"></i>
                        <h2 class="text-base font-extrabold text-stone-800">Jam Operasional</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Senin – Jumat</label>
                            <input type="text" name="jam_senin_jumat" value="{{ old('jam_senin_jumat', '08:00 – 16:00') }}" 
                                class="w-full px-4 py-2.5 bg-yellow-50/60 border border-yellow-200 text-yellow-900 rounded-xl text-sm font-semibold focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Sabtu</label>
                            <input type="text" name="jam_sabtu" value="{{ old('jam_sabtu', '08:00 – 12:00') }}" 
                                class="w-full px-4 py-2.5 bg-yellow-50/60 border border-yellow-200 text-yellow-900 rounded-xl text-sm font-semibold focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 mb-1.5">Minggu / Hari Libur</label>
                            <input type="text" name="jam_minggu" value="{{ old('jam_minggu', 'Tutup') }}" 
                                class="w-full px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-semibold focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi Simpan Perubahan --}}
                <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm text-center">
                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-stone-900 font-extrabold px-6 py-3.5 rounded-2xl shadow-md transition transform active:scale-95 flex items-center justify-center gap-2">
                        <i class="bi bi-floppy-fill text-lg"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                    <p class="text-[11px] text-stone-400 mt-3 font-medium">Perubahan akan langsung diterapkan ke sistem.</p>
                </div>

            </div>

        </div>
    </form>

@endsection