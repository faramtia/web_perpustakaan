@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $fmt = fn ($n) => number_format((int) $n, 0, ',', '.');
    $maks = max(1, collect($grafik)->max('jumlah'));
    $total = max(1, $stokTersedia + $sedangDipinjam);
    $pTersedia = (int) round($stokTersedia / $total * 100);
    $pDipinjam = 100 - $pTersedia;
    $isAdmin = auth()->user()->role === 'admin';

    $notif = [
        [$menungguVerifikasi, 'pengajuan peminjaman menunggu verifikasi', 'bi-hourglass-split', 'bg-yellow-400', route('petugas.peminjaman.index', ['status' => 'Menunggu'])],
        [$terlambat, 'buku terlambat dikembalikan', 'bi-exclamation-lg', 'bg-red-500', route('petugas.peminjaman.index', ['status' => 'Terlambat'])],
        [$reservasiMenunggu, 'reservasi menunggu diproses', 'bi-calendar-check', 'bg-blue-500', route('petugas.reservasi.index', ['status' => 'Menunggu'])],
        [$taMenunggu, 'tugas akhir menunggu review', 'bi-mortarboard', 'bg-purple-500', route('petugas.tugas-akhir.index', ['status' => 'Menunggu'])],
        [$feedbackBelumDibalas, 'pesan belum dibalas', 'bi-chat-dots', 'bg-sky-500', route('petugas.feedback.index', ['status' => 'Belum Dibalas'])],
        [$kunjunganHariIni, 'kunjungan hari ini', 'bi-check-lg', 'bg-emerald-500', route('petugas.absensi.index')],
    ];

    $aksi = [
        ['Tambah Buku', route('admin.buku.create'), 'bi-journal-plus'],
        ['Proses Pengembalian', route('petugas.peminjaman.index', ['tampil' => 'belum']), 'bi-arrow-repeat'],
        ['Catat Absensi', route('petugas.absensi.index'), 'bi-person-check'],
        ['Buat Laporan', route('petugas.laporan.index'), 'bi-bar-chart'],
    ];
@endphp

    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-sm text-stone-500">Dashboard {{ $isAdmin ? 'Administrator' : 'Petugas' }}</p>
            <h1 class="text-2xl font-extrabold text-stone-900 sm:text-3xl">Selamat datang, {{ auth()->user()->nama }}!</h1>
            <p class="mt-1 text-sm text-stone-500">Kelola koleksi, layanan pengguna, dan aktivitas perpustakaan.</p>
        </div>
        <div class="text-right text-sm text-stone-600">
            <p class="font-semibold">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-stone-400">Pukul {{ now()->format('H.i') }} WIB</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-stat-card label="Total Eksemplar" :value="$fmt($totalBuku)" :hint="$fmt($judulBuku).' judul'" icon="bi-book" tone="yellow" :href="route('admin.buku.index')" />
        <x-stat-card label="Anggota" :value="$fmt($totalAnggota)" hint="mahasiswa & dosen" icon="bi-people" tone="blue" :href="$isAdmin ? route('admin.pengguna.index') : null" />
        <x-stat-card label="Sedang Dipinjam" :value="$fmt($peminjamanAktif)" hint="transaksi aktif" icon="bi-box-arrow-up-right" tone="green" :href="route('petugas.peminjaman.index', ['tampil' => 'belum'])" />
        <x-stat-card label="Terlambat" :value="$fmt($terlambat)" hint="lewat jatuh tempo" icon="bi-clock-history" tone="red" :href="route('petugas.peminjaman.index', ['status' => 'Terlambat'])" />
        <x-stat-card label="Denda Belum Lunas" :value="'Rp '.$fmt($totalDenda)" hint="belum dibayar" icon="bi-coin" tone="purple" :href="route('petugas.denda.index', ['status' => 'Belum Lunas'])" />
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        {{-- Grafik --}}
        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-stone-800">Peminjaman Minggu Ini</h2>
                <span class="chip bg-stone-100 text-stone-500">Sen–Min</span>
            </div>
            <div class="flex h-44 items-end justify-between gap-2 border-b border-l border-stone-200 px-1">
                @foreach ($grafik as $g)
                    <div class="flex h-full flex-1 flex-col items-center justify-end" title="{{ $g['jumlah'] }} peminjaman">
                        <span class="mb-1 text-[10px] text-stone-400">{{ $g['jumlah'] }}</span>
                        <div class="w-full rounded-t bg-gradient-to-t from-yellow-500 to-yellow-200" style="height: {{ max(3, $g['jumlah'] / $maks * 100) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-1 flex justify-between gap-2 px-1 text-xs text-stone-500">
                @foreach ($grafik as $g)<span class="flex-1 text-center">{{ $g['hari'] }}</span>@endforeach
            </div>
        </div>

        {{-- Status koleksi --}}
        <div class="card p-5">
            <h2 class="mb-4 font-bold text-stone-800">Status Koleksi</h2>
            <div class="flex items-center gap-5">
                <div class="relative h-36 w-36 shrink-0 rounded-full" style="background: conic-gradient(#eab308 0 {{ $pTersedia }}%, #44403c {{ $pTersedia }}% 100%)">
                    <div class="absolute inset-4 flex flex-col items-center justify-center rounded-full bg-white">
                        <span class="text-xl font-extrabold">{{ $fmt($stokTersedia + $sedangDipinjam) }}</span>
                        <span class="text-xs text-stone-500">Eksemplar</span>
                    </div>
                </div>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2"><span class="mt-1 h-3 w-3 rounded-full" style="background:#eab308"></span><span><b>Tersedia</b> {{ $pTersedia }}%<br><span class="text-xs text-stone-400">{{ $fmt($stokTersedia) }} buku</span></span></li>
                    <li class="flex items-start gap-2"><span class="mt-1 h-3 w-3 rounded-full" style="background:#44403c"></span><span><b>Dipinjam</b> {{ $pDipinjam }}%<br><span class="text-xs text-stone-400">{{ $fmt($sedangDipinjam) }} buku</span></span></li>
                </ul>
            </div>
        </div>

        {{-- Aksi cepat --}}
        <div class="card p-5">
            <h2 class="mb-4 font-bold text-stone-800">Aksi Cepat</h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($aksi as [$label, $url, $ikon])
                    <a href="{{ $url }}" class="flex h-24 flex-col items-center justify-center gap-2 rounded-xl bg-yellow-50 p-3 text-center text-sm font-semibold text-yellow-800 transition hover:bg-yellow-100 hover:shadow">
                        <i class="bi {{ $ikon }} text-2xl"></i>{{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        <div class="space-y-4 xl:col-span-2">
            {{-- Antrian verifikasi --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4">
                    <h2 class="font-bold text-stone-800">Menunggu Verifikasi <span class="chip ml-1 bg-yellow-100 text-yellow-700">{{ $menungguVerifikasi }}</span></h2>
                    <a href="{{ route('petugas.peminjaman.index', ['status' => 'Menunggu']) }}" class="btn btn-sm btn-soft">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="tbl">
                        <tbody>
                            @forelse ($antrianPeminjaman as $p)
                                <tr>
                                    <td><x-user-cell :user="$p->user" /></td>
                                    <td class="max-w-xs truncate text-stone-600">{{ $p->judul_buku }}</td>
                                    <td class="whitespace-nowrap text-stone-500">{{ optional($p->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>
                                        <div class="flex justify-end gap-2">
                                            <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                                @csrf <input type="hidden" name="keputusan" value="setuju">
                                                <button class="btn btn-sm btn-success">Setujui</button>
                                            </form>
                                            <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}" onsubmit="return confirm('Tolak pengajuan ini?')">
                                                @csrf <input type="hidden" name="keputusan" value="tolak">
                                                <button class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-empty-state cols="4" icon="bi-check2-circle" title="Tidak ada pengajuan yang menunggu" />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Transaksi terbaru --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4">
                    <h2 class="font-bold text-stone-800">Transaksi Terbaru</h2>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="btn btn-sm btn-soft">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="tbl">
                        <thead><tr><th>Tanggal</th><th>Anggota</th><th>Buku</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($peminjamanTerbaru as $p)
                                <tr>
                                    <td class="whitespace-nowrap">{{ optional($p->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $p->user->nama ?? '-' }}</td>
                                    <td class="max-w-xs truncate text-stone-600">{{ $p->judul_buku }}</td>
                                    <td><x-status-badge :status="$p->status_tampil" /></td>
                                </tr>
                            @empty
                                <x-empty-state cols="4" title="Belum ada transaksi" />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="card p-5">
                <h2 class="mb-3 font-bold text-stone-800">Perlu Perhatian</h2>
                <ul class="divide-y divide-stone-100">
                    @foreach ($notif as [$jumlah, $teks, $ikon, $warna, $url])
                        <li>
                            <a href="{{ $url }}" class="flex items-center gap-3 rounded py-2.5 hover:bg-stone-50">
                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full {{ $warna }} text-white"><i class="bi {{ $ikon }}"></i></span>
                                <span class="flex-1 text-sm"><b>{{ $jumlah }}</b> {{ $teks }}</span>
                                <i class="bi bi-chevron-right text-stone-300"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="font-bold text-stone-800">Event Mendatang</h2>
                    <a href="{{ route('event.index') }}" class="btn btn-sm btn-soft">Semua →</a>
                </div>
                @if ($eventMendatang)
                    <a href="{{ route('event.show', $eventMendatang) }}" class="flex items-center gap-4">
                        <div class="w-14 shrink-0 rounded-xl bg-yellow-100 py-2 text-center text-yellow-800">
                            <p class="text-xl font-extrabold leading-none">{{ $eventMendatang->tanggal_mulai->format('d') }}</p>
                            <p class="text-xs font-semibold">{{ $eventMendatang->tanggal_mulai->translatedFormat('M') }}</p>
                        </div>
                        <div class="min-w-0 text-sm">
                            <p class="truncate font-bold">{{ $eventMendatang->judul }}</p>
                            <p class="truncate text-xs text-stone-500"><i class="bi bi-geo-alt"></i> {{ $eventMendatang->lokasi ?: '-' }}</p>
                            <span class="chip mt-1 bg-yellow-100 text-yellow-700">{{ $eventMendatang->peserta_count }} peserta</span>
                        </div>
                    </a>
                @else
                    <p class="text-sm italic text-stone-400">Belum ada event mendatang.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
