<<<<<<< HEAD
@extends('layouts.app')
@section('title', 'Artikel & Blog Perpustakaan')
=======
@extends('layouts.guest')
@section('title', 'Artikel & Blog')
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
@section('page-title', 'Artikel & Blog')

@section('content')

    {{-- Header Artikel & Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl font-bold shadow-sm">
                <i class="bi bi-newspaper"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold leading-tight text-stone-900">Artikel & Blog</h1>
                <p class="text-sm text-stone-500">Berita, tips, panduan, dan tulisan informatif dari perpustakaan.</p>
            </div>
        </div>

<<<<<<< HEAD
        {{-- Tombol Tulis Artikel --}}
        <a href="{{ url('/artikel/create') }}" class="px-5 py-3 bg-stone-900 hover:bg-stone-800 text-white font-extrabold rounded-2xl text-xs transition flex items-center justify-center gap-2 shadow-md transform active:scale-95">
            <i class="bi bi-plus-lg text-yellow-400 text-sm"></i> Tulis Artikel Baru
        </a>
=======
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($artikel as $a)
            <div class="bg-white border rounded-xl overflow-hidden">
                <div class="h-32 bg-gold-100 flex items-center justify-center text-4xl">📰</div>
                <div class="p-4">
                    <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ $a->kategori->nama_kategori ?? 'Artikel' }}</p>
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $a->judul }}</h3>
                    <p class="text-xs text-gray-400 mb-2">Oleh {{ $a->penulis->name ?? '-' }} &middot; {{ optional($a->tanggal_upload)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $a->isi_artikel }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-400 italic col-span-full text-center py-10">Belum ada artikel dipublikasikan.</p>
        @endforelse
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
    </div>

    {{-- Notifikasi Berhasil (Jika Ada) --}}
    @if (session('success'))
        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-2xl px-5 py-3.5 mb-6 text-sm flex items-center gap-3 shadow-sm">
            <i class="bi bi-check-circle-fill text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Grid Daftar Artikel --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Item Artikel 1 (Bisa di-looping menggunakan @foreach jika datanya dari database) --}}
        <div class="bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
            <div>
                {{-- Ilustrasi / Gambar Header Artikel --}}
                <div class="relative overflow-hidden aspect-video bg-gradient-to-br from-yellow-50 to-amber-100 flex items-center justify-center group-hover:scale-105 transition duration-500">
                    <i class="bi bi-journal-text text-5xl text-yellow-600/70"></i>
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-yellow-800 text-[11px] font-extrabold px-3 py-1 rounded-xl shadow-sm">
                        Akademik
                    </span>
                </div>

                {{-- Konten Teks Artikel --}}
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-stone-400 mb-2.5 font-medium">
                        <span><i class="bi bi-calendar-event text-yellow-600 mr-1"></i> 10 Okt 2026</span>
                        <span>•</span>
                        <span><i class="bi bi-person text-yellow-600 mr-1"></i> Admin Perpus</span>
                    </div>

                    <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-2 line-clamp-2">
                        Manajemen Waktu untuk Mahasiswa: Kunci Sukses Kuliah & Literasi
                    </h3>
                    
                    <p class="text-xs text-stone-500 line-clamp-3 leading-relaxed">
                        Strategi efektif mengatur waktu antara jadwal kuliah, tugas akademik, dan kunjungan rutin ke perpustakaan demi meningkatkan produktivitas belajar...
                    </p>
                </div>
            </div>

            {{-- Footer Card (Tombol Baca Selengkapnya) --}}
            <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-stone-50">
                <a href="{{ url('/artikel/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1.5 transition">
                    <span>Baca Selengkapnya</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <div class="flex items-center gap-1">
                    <a href="#" class="w-8 h-8 rounded-xl bg-stone-50 hover:bg-yellow-50 text-stone-600 hover:text-yellow-700 grid place-items-center transition" title="Edit">
                        <i class="bi bi-pencil-fill text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Item Artikel 2 --}}
        <div class="bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
            <div>
                <div class="relative overflow-hidden aspect-video bg-gradient-to-br from-yellow-50 to-amber-100 flex items-center justify-center group-hover:scale-105 transition duration-500">
                    <i class="bi bi-book-half text-5xl text-yellow-600/70"></i>
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-yellow-800 text-[11px] font-extrabold px-3 py-1 rounded-xl shadow-sm">
                        Literasi
                    </span>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-stone-400 mb-2.5 font-medium">
                        <span><i class="bi bi-calendar-event text-yellow-600 mr-1"></i> 08 Okt 2026</span>
                        <span>•</span>
                        <span><i class="bi bi-person text-yellow-600 mr-1"></i> Petugas</span>
                    </div>

                    <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-2 line-clamp-2">
                        Budaya Membaca di Kalangan Mahasiswa Politeknik
                    </h3>
                    
                    <p class="text-xs text-stone-500 line-clamp-3 leading-relaxed">
                        Mengulas seberapa penting peran literasi digital dan fisik dalam membangun pola pikir kritis serta inovatif di era modern saat ini...
                    </p>
                </div>
            </div>

            <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-stone-50">
                <a href="{{ url('/artikel/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1.5 transition">
                    <span>Baca Selengkapnya</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <div class="flex items-center gap-1">
                    <a href="#" class="w-8 h-8 rounded-xl bg-stone-50 hover:bg-yellow-50 text-stone-600 hover:text-yellow-700 grid place-items-center transition" title="Edit">
                        <i class="bi bi-pencil-fill text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Item Artikel 3 --}}
        <div class="bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between group">
            <div>
                <div class="relative overflow-hidden aspect-video bg-gradient-to-br from-yellow-50 to-amber-100 flex items-center justify-center group-hover:scale-105 transition duration-500">
                    <i class="bi bi-lightbulb text-5xl text-yellow-600/70"></i>
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-yellow-800 text-[11px] font-extrabold px-3 py-1 rounded-xl shadow-sm">
                        Fasilitas
                    </span>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-stone-400 mb-2.5 font-medium">
                        <span><i class="bi bi-calendar-event text-yellow-600 mr-1"></i> 05 Okt 2026</span>
                        <span>•</span>
                        <span><i class="bi bi-person text-yellow-600 mr-1"></i> Admin</span>
                    </div>

                    <h3 class="text-base font-extrabold text-stone-900 group-hover:text-yellow-600 transition mb-2 line-clamp-2">
                        Perpustakaan sebagai Pusat Sumber Pembelajaran Utama
                    </h3>
                    
                    <p class="text-xs text-stone-500 line-clamp-3 leading-relaxed">
                        Nikmati berbagai fasilitas ruang baca nyaman, akses jurnal online terpadu, dan koleksi referensi lengkap yang siap mendukung riset Anda...
                    </p>
                </div>
            </div>

            <div class="px-6 pb-6 pt-2 flex items-center justify-between border-t border-stone-50">
                <a href="{{ url('/artikel/detail') }}" class="text-xs font-extrabold text-stone-900 hover:text-yellow-600 flex items-center gap-1.5 transition">
                    <span>Baca Selengkapnya</span>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <div class="flex items-center gap-1">
                    <a href="#" class="w-8 h-8 rounded-xl bg-stone-50 hover:bg-yellow-50 text-stone-600 hover:text-yellow-700 grid place-items-center transition" title="Edit">
                        <i class="bi bi-pencil-fill text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection