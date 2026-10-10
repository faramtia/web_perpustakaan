@extends('layouts.app')
@section('title', 'Event & Agenda Perpustakaan')
@section('page-title', 'Event & Agenda')

@section('content')

    {{-- Header Event & Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold leading-tight text-stone-900">Event & Agenda</h1>
                <p class="text-sm text-stone-500">Jadwal seminar, workshop, dan pelatihan literasi di perpustakaan.</p>
            </div>
        </div>

        {{-- Tombol Tambah Event --}}
        <a href="{{ url('/event/create') }}" class="px-5 py-3 bg-stone-900 hover:bg-stone-800 text-white font-extrabold rounded-2xl text-xs transition flex items-center justify-center gap-2 shadow-md transform active:scale-95">
            <i class="bi bi-plus-lg text-yellow-400 text-sm"></i> Tambah Event Baru
        </a>
    </div>

    {{-- Notifikasi Berhasil (Jika Ada) --}}
    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl px-5 py-3.5 mb-6 text-sm flex items-center gap-3 shadow-sm">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Grid Daftar Event --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Item Event 1 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 10 Sep 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #1
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Seminar Literasi Digital Mahasiswa
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Meningkatkan pemahaman sivitas akademika dalam menyaring informasi valid di era internet.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/100 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Item Event 2 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 15 Sep 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #2
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Pelatihan Penggunaan Perpustakaan Digital
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Panduan praktis memaksimalkan akses e-book dan jurnal online perpustakaan kampus.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/50 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Item Event 3 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 20 Sep 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #3
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Workshop Penulisan Karya Ilmiah
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Strategi jitu menyusun karya tulis ilmiah yang memenuhi standar publikasi nasional.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/75 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Item Event 4 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 25 Sep 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #4
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Sosialisasi Repository Tugas Akhir Mahasiswa
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Tata cara pengumpulan mandiri arsip skripsi dan tugas akhir ke sistem repository.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/100 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Item Event 5 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 01 Okt 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #5
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Pelatihan Mencari Referensi Jurnal Nasional & Internasional
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Teknik penelusuran literatur ilmiah menggunakan database bereputasi tinggi.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/60 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Item Event 6 --}}
        <div class="bg-white rounded-3xl p-6 border border-stone-100 shadow-sm hover:shadow-md transition flex flex-col justify-between group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-yellow-50 text-yellow-800 border border-yellow-200/60 flex items-center gap-1.5">
                        <i class="bi bi-calendar3"></i> 05 Okt 2026
                    </span>
                    <span class="w-8 h-8 rounded-xl bg-stone-50 group-hover:bg-yellow-50 text-stone-600 group-hover:text-yellow-700 grid place-items-center transition text-xs font-bold">
                        #6
                    </span>
                </div>

                <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-3 line-clamp-2">
                    Seminar Manajemen Referensi Menggunakan Mendeley
                </h3>

                <p class="text-xs text-stone-500 mb-4 line-clamp-2 leading-relaxed">
                    Praktik langsung pengelolaan sitasi dan bibliografi otomatis anti ribet.
                </p>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <span class="text-xs font-bold text-stone-600 flex items-center gap-1.5">
                    <i class="bi bi-people-fill text-yellow-600"></i> 2/80 peserta
                </span>
                <a href="{{ url('/event/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1 transition">
                    <span>Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

@endsection