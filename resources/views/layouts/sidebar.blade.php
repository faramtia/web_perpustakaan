@php
    // Sidebar PNL Library — desain baru.
    // Format menu: [label, nama route, pola route aktif (string / array), ikon]
    // Route yang belum dibuat otomatis menjadi '#', jadi tidak error.
    $role  = auth()->user()->role ?? '';
    $belum = request('tampil') === 'belum';   // sedang membuka daftar "belum dikembalikan"

    $grup = [
        'Utama' => [
            ['Dashboard',  'petugas.dashboard',        'petugas.dashboard',  'bi-house-door'],
            ['Data Buku',  'admin.buku.index',         'admin.buku.*',       'bi-book'],
        ],
        'Transaksi' => [
            ['Peminjaman',   'petugas.peminjaman.index', 'petugas.peminjaman.*', 'bi-box-arrow-up-right'],
            ['Pengembalian', 'petugas.peminjaman.index', 'petugas.peminjaman.*', 'bi-arrow-repeat'],
            ['Reservasi',    'petugas.reservasi.index',  'petugas.reservasi.*',  'bi-calendar-check'],
            ['Denda',        'petugas.denda.index',      'petugas.denda.*',      'bi-coin'],
        ],
        'Layanan' => [
            ['Absensi',     'petugas.absensi.index',     'petugas.absensi.*',     'bi-person-check'],
            ['E-Jurnal',    'ejurnal.index',             'ejurnal.*',             'bi-file-earmark-text'],
            ['Feedback',    'petugas.feedback.index',    'petugas.feedback.*',    'bi-chat-dots'],
            ['Tugas Akhir', 'petugas.tugas-akhir.index', 'petugas.tugas-akhir.*', 'bi-mortarboard'],
            ['Event',       'petugas.event.index',       'petugas.event.*',       'bi-calendar-event'],
            ['Artikel',     'artikel.index',             'artikel.*',             'bi-newspaper'],
        ],
        'Lainnya' => [
            ['Gallery', 'petugas.gallery.index', ['petugas.gallery.*', 'gallery.*'], 'bi-images'],
            ['Laporan', 'petugas.laporan.index', 'petugas.laporan.*',                'bi-bar-chart'],
            ['Setting', 'petugas.setting.index', 'petugas.setting.*',                'bi-gear'],
        ],
    ];

    // Kategori & Lokasi hanya boleh dibuka admin (kalau petugas, muncul 403)
    if ($role === 'admin') {
        array_splice($grup['Utama'], 2, 0, [
            ['Kategori', 'admin.kategori.index', 'admin.kategori.*', 'bi-grid'],
            ['Lokasi',   'admin.lokasi.index',   'admin.lokasi.*',   'bi-geo-alt'],
        ]);
    }
@endphp

<aside class="w-64 bg-white border-r border-stone-200 fixed left-0 top-0 h-full flex flex-col z-20">

    {{-- Logo & nama aplikasi --}}
    <div class="h-20 shrink-0 flex items-center gap-3 px-5 border-b border-stone-100">
        <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL"
             class="h-11 w-auto object-contain drop-shadow-sm">
        <div class="leading-tight">
            <h1 class="font-extrabold text-lg tracking-tight text-stone-900">PNL Library</h1>
            <p class="text-[11px] text-stone-400">Politeknik Negeri Lhokseumawe</p>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 [scrollbar-width:thin]">

        @foreach ($grup as $namaGrup => $menu)
            <p class="px-3 mb-2 mt-4 first:mt-0 text-[10px] font-bold uppercase tracking-[0.14em] text-stone-400">
                {{ $namaGrup }}
            </p>

            <div class="space-y-1">
                @foreach ($menu as [$label, $route, $pattern, $icon])
                    @php
                        $pola = (array) $pattern;
                        $href = Route::has($route) ? route($route) : '#';

                        if ($label === 'Pengembalian') {
                            // Membuka halaman Peminjaman yang hanya menampilkan buku belum kembali
                            $href   = Route::has($route) ? route($route, ['tampil' => 'belum']) : '#';
                            $active = $belum && request()->routeIs(...$pola);
                        } else {
                            $active = request()->routeIs(...$pola) && ! ($label === 'Peminjaman' && $belum);
                        }
                    @endphp

                    <a href="{{ $href }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition
                              {{ $active
                                  ? 'bg-yellow-400 text-stone-900 font-semibold shadow-sm'
                                  : 'text-stone-600 hover:bg-yellow-50 hover:text-stone-900' }}">
                        <span class="w-8 h-8 shrink-0 grid place-items-center rounded-lg text-base transition
                                     {{ $active
                                         ? 'bg-white/60 text-stone-900'
                                         : 'bg-stone-100 text-stone-500 group-hover:bg-yellow-100 group-hover:text-yellow-700' }}">
                            <i class="bi {{ $icon }}"></i>
                        </span>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        @endforeach

    </nav>

    {{-- Logout --}}
    <div class="shrink-0 p-3 border-t border-stone-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition">
                <span class="w-8 h-8 grid place-items-center rounded-lg bg-red-50 text-base">
                    <i class="bi bi-box-arrow-right"></i>
                </span>
                Keluar
            </button>
        </form>
    </div>

</aside>