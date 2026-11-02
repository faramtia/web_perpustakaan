<footer class="mt-10 border-t border-stone-200 bg-white">
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-6 px-4 py-8 text-sm text-stone-500 sm:grid-cols-3">
        <div>
            <div class="mb-2 flex items-center gap-2">
                <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-8 w-8 object-contain">
                <span class="font-bold text-stone-800">Perpustakaan PNL</span>
            </div>
            <p>Jl. Banda Aceh–Medan Km. 280,3, Buketrata, Lhokseumawe, Aceh 24301.</p>
            <a href="{{ route('about') }}" class="mt-2 inline-block font-semibold text-yellow-700 hover:underline">Tentang Kami →</a>
        </div>
        <div>
            <p class="mb-2 font-bold text-stone-800">Layanan</p>
            <ul class="space-y-1">
                <li><a href="{{ route('katalog.index') }}" class="hover:text-stone-900">Katalog &amp; Koleksi</a></li>
                <li><a href="{{ route('tugas-akhir.index') }}" class="hover:text-stone-900">Tugas Akhir</a></li>
                <li><a href="{{ route('event.index') }}" class="hover:text-stone-900">Event Semester</a></li>
                <li><a href="{{ route('artikel.index') }}" class="hover:text-stone-900">Artikel &amp; Blog</a></li>
            </ul>
        </div>
        <div>
            <p class="mb-2 font-bold text-stone-800">Kontak</p>
            <p>Telp. (0645) 42670</p>
            <p>Senin&ndash;Jumat, 08.00&ndash;17.30 WIB</p>
        </div>
    </div>
    <div class="pb-6 text-center text-xs text-stone-400">
        &copy; {{ date('Y') }} Perpustakaan Politeknik Negeri Lhokseumawe
    </div>
</footer>
