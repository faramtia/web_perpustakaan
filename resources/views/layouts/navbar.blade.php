<header class="bg-yellow-400 px-6 py-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
    <!-- Kotak Pencarian -->
    <div class="flex-1 max-w-xl">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-500">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" placeholder="Cari buku, anggota, peminjaman, dan lainnya..." 
                class="w-full pl-10 pr-4 py-2.5 bg-white/90 focus:bg-white text-stone-800 placeholder-stone-400 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 transition shadow-sm">
        </div>
    </div>

    <!-- Bagian Kanan (Notifikasi & Profil) -->
    <div class="flex items-center gap-4 ml-6">
        <!-- Tombol Lonceng Notifikasi -->
        <button class="w-10 h-10 rounded-xl bg-white/60 hover:bg-white text-stone-800 grid place-items-center transition shadow-sm">
            <i class="bi bi-bell text-base"></i>
        </button>

        <!-- Profil User -->
        <div class="flex items-center gap-3 bg-white/60 hover:bg-white px-3 py-1.5 rounded-2xl transition shadow-sm">
            <div class="text-right">
                <p class="text-xs font-bold text-stone-900">{{ auth()->user()->name ?? 'dini' }}</p>
                <p class="text-[10px] text-stone-600 font-medium">Petugas Perpustakaan</p>
            </div>
            <div class="w-9 h-9 rounded-xl bg-stone-900 text-yellow-400 grid place-items-center font-bold text-xs shadow-inner">
                {{ strtoupper(substr(auth()->user()->name ?? 'DI', 0, 2)) }}
            </div>
        </div>
    </div>
</header>