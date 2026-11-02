@php
    // Menu sidebar untuk semua role, dikelompokkan seperti template SIAKAD: Master / Transaksi / Layanan / Laporan.
    // Format item: [label, nama route, pola route aktif, ikon, parameter (opsional)]
    $role = auth()->user()->role ?? '';
    $belum = request('tampil') === 'belum';

    $dashboard = [
        'admin' => 'admin.dashboard',
        'petugas' => 'petugas.dashboard',
    ][$role] ?? 'anggota.dashboard';

    $utama = [['Dashboard', $dashboard, $dashboard, 'bi-house-door']];

    if ($role === 'admin') {
        $menu = [
            'Utama' => $utama,
            'Master Data' => [
                ['Data Buku', 'admin.buku.index', 'admin.buku.*', 'bi-book'],
                ['Kategori', 'admin.kategori.index', 'admin.kategori.*', 'bi-grid'],
                ['Lokasi Rak', 'admin.lokasi.index', 'admin.lokasi.*', 'bi-geo-alt'],
                ['Tipe Koleksi', 'admin.tipe-koleksi.index', 'admin.tipe-koleksi.*', 'bi-tags'],
                ['Pengguna', 'admin.pengguna.index', 'admin.pengguna.*', 'bi-people'],
            ],
        ];
    } elseif ($role === 'petugas') {
        $menu = [
            'Utama' => $utama,
            'Master Data' => [
                ['Data Buku', 'admin.buku.index', 'admin.buku.*', 'bi-book'],
            ],
        ];
    } else {
        $menu = [
            'Utama' => $utama,
            'Koleksi' => [
                ['Cari Buku', 'katalog.index', 'katalog.*', 'bi-search'],
                ['Koleksi Tugas Akhir', 'tugas-akhir.index', 'tugas-akhir.*', 'bi-mortarboard'],
            ],
        ];
    }

    if (in_array($role, ['admin', 'petugas'], true)) {
        $menu['Transaksi'] = [
            ['Peminjaman', 'petugas.peminjaman.index', 'petugas.peminjaman.*', 'bi-box-arrow-up-right'],
            ['Pengembalian', 'petugas.peminjaman.index', 'petugas.peminjaman.*', 'bi-arrow-repeat', ['tampil' => 'belum']],
            ['Reservasi', 'petugas.reservasi.index', 'petugas.reservasi.*', 'bi-calendar-check'],
            ['Denda', 'petugas.denda.index', 'petugas.denda.*', 'bi-coin'],
        ];
        $menu['Layanan'] = [
            ['Absensi', 'petugas.absensi.index', 'petugas.absensi.*', 'bi-person-check'],
            ['Kiosk Absensi', 'kiosk.absensi', 'kiosk.*', 'bi-display'],
            ['Feedback', 'petugas.feedback.index', 'petugas.feedback.*', 'bi-chat-dots'],
            ['Tugas Akhir', 'petugas.tugas-akhir.index', 'petugas.tugas-akhir.*', 'bi-mortarboard'],
            ['Event', 'event.index', ['event.*'], 'bi-calendar-event'],
            ['Artikel', 'artikel.index', ['artikel.*', 'admin.artikel.*'], 'bi-newspaper'],
            ['Gallery', 'gallery.index', 'gallery.*', 'bi-images'],
        ];
        $menu['Laporan'] = [
            ['Laporan', 'petugas.laporan.index', 'petugas.laporan.*', 'bi-bar-chart'],
        ];
        if ($role === 'admin') {
            $menu['Laporan'][] = ['Pengaturan', 'admin.setting.index', 'admin.setting.*', 'bi-gear'];
        }
    } else {
        $menu['Transaksi'] = [
            ['Peminjaman Saya', 'anggota.peminjaman.index', 'anggota.peminjaman.*', 'bi-box-arrow-up-right'],
            ['Reservasi Saya', 'anggota.reservasi.index', 'anggota.reservasi.*', 'bi-calendar-check'],
            ['Denda Saya', 'anggota.denda.index', 'anggota.denda.*', 'bi-coin'],
        ];
        $menu['Layanan'] = [
            ['Riwayat Absensi', 'anggota.absensi.index', 'anggota.absensi.*', 'bi-person-check'],
            ['Tugas Akhir', 'anggota.tugas-akhir.index', 'anggota.tugas-akhir.*', 'bi-mortarboard'],
            ['Tanya Pustakawan', 'anggota.feedback.index', 'anggota.feedback.*', 'bi-chat-dots'],
            ['Event Semester', 'event.index', 'event.*', 'bi-calendar-event'],
            ['Artikel', 'artikel.index', 'artikel.*', 'bi-newspaper'],
            ['Gallery', 'gallery.index', 'gallery.*', 'bi-images'],
        ];
    }
@endphp

<aside id="sidebar"
       class="fixed left-0 top-0 z-30 flex h-full w-64 -translate-x-full flex-col border-r border-stone-200 bg-white transition-transform duration-200 lg:translate-x-0 print:hidden">

    {{-- Logo & nama aplikasi --}}
    <a href="{{ route('home') }}" class="flex h-20 shrink-0 items-center gap-3 border-b border-stone-100 px-5">
        <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-11 w-auto object-contain drop-shadow-sm">
        <div class="leading-tight">
            <h1 class="text-lg font-extrabold tracking-tight text-stone-900">PNL Library</h1>
            <p class="text-[11px] text-stone-400">Politeknik Negeri Lhokseumawe</p>
        </div>
    </a>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 [scrollbar-width:thin]">
        @foreach ($menu as $namaGrup => $items)
            <p class="mb-2 mt-4 px-3 text-[10px] font-bold uppercase tracking-[0.14em] text-stone-400 first:mt-0">{{ $namaGrup }}</p>

            <div class="space-y-1">
                @foreach ($items as $item)
                    @php
                        [$label, $route, $pattern, $icon] = $item;
                        $param = $item[4] ?? [];
                        $pola = (array) $pattern;
                        $href = Route::has($route) ? route($route, $param) : '#';

                        if ($label === 'Pengembalian') {
                            $active = $belum && request()->routeIs(...$pola);
                        } elseif ($label === 'Peminjaman') {
                            $active = ! $belum && request()->routeIs(...$pola);
                        } else {
                            $active = request()->routeIs(...$pola);
                        }
                    @endphp

                    <a href="{{ $href }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition
                              {{ $active ? 'bg-yellow-400 font-semibold text-stone-900 shadow-sm' : 'text-stone-600 hover:bg-yellow-50 hover:text-stone-900' }}">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-base transition
                                     {{ $active ? 'bg-white/60 text-stone-900' : 'bg-stone-100 text-stone-500 group-hover:bg-yellow-100 group-hover:text-yellow-700' }}">
                            <i class="bi {{ $icon }}"></i>
                        </span>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    {{-- Keluar --}}
    <div class="shrink-0 border-t border-stone-100 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-red-50 text-base"><i class="bi bi-box-arrow-right"></i></span>
                Keluar
            </button>
        </form>
    </div>
</aside>
