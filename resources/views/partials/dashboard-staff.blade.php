{{-- Dashboard bersama petugas & admin: kartu statistik, grafik, transaksi, tugas --}}
@php
    $menuCepat = [
        ['Data Buku', 'Tambah, edit, kelola koleksi buku', '📖', route('katalog.index')],
        ['Peminjaman', 'Kelola transaksi peminjaman', '🔄', route('petugas.peminjaman.index')],
        ['Catat Peminjaman', 'Catat peminjaman di meja', '✍️', route('petugas.peminjaman.catat')],
        ['Absensi', 'Rekap kunjungan perpustakaan', '🧾', route('petugas.absensi.index')],
        ['Event', 'Kelola kegiatan perpustakaan', '📅', route('petugas.event.index')],
        ['E-Publication', 'Kelola jurnal & tugas akhir', '📚', route('epublication.index')],
    ];
@endphp

<div class="mb-5">
    <h2 class="text-xl font-semibold text-gray-800">Selamat datang, {{ auth()->user()->name }}!</h2>
    <p class="text-sm text-gray-500">Kelola koleksi, layanan, dan aktivitas perpustakaan dengan mudah dan efisien.</p>
</div>

{{-- Kartu statistik --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @foreach ($stats as $s)
        <div class="bg-white rounded-xl border border-t-2 border-t-gold-500 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 shrink-0 rounded-lg bg-gold-50 flex items-center justify-center text-lg">{{ $s['icon'] }}</div>
                <p class="text-xs text-gray-500 leading-tight">{{ $s['label'] }}</p>
            </div>
            <p class="mt-3 text-xl font-semibold text-gray-800 truncate">{{ $s['value'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Menu cepat --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-t-2 border-t-gold-500 p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Menu Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($menuCepat as [$judul, $ket, $icon, $url])
                <a href="{{ $url }}" class="flex items-center gap-3 border rounded-lg p-3 hover:border-gold-500 hover:bg-gold-50 transition">
                    <div class="w-10 h-10 shrink-0 rounded-lg bg-gold-50 flex items-center justify-center text-lg">{{ $icon }}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-700">{{ $judul }}</p>
                        <p class="text-xs text-gray-500">{{ $ket }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Grafik --}}
    <div class="bg-white rounded-xl border border-t-2 border-t-gold-500 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">Statistik Peminjaman</h3>
            <select id="rentangGrafik" class="text-xs border rounded-lg px-2 py-1 text-gray-600">
                <option value="7">7 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir</option>
            </select>
        </div>
        <div class="relative h-56"><canvas id="grafikPeminjaman"></canvas></div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Transaksi terbaru --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-t-2 border-t-gold-500 overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">Transaksi Terbaru</h3>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-sm text-gold-700 hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Jenis</th>
                        <th class="px-4 py-3 font-medium">Judul Buku</th>
                        <th class="px-4 py-3 font-medium">Anggota</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Petugas</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($transaksi as $i => $p)
                        <tr>
                            <td class="px-4 py-3">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $p->status === \App\Models\Peminjaman::DIKEMBALIKAN ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $p->status === \App\Models\Peminjaman::DIKEMBALIKAN ? 'Pengembalian' : 'Peminjaman' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-[12rem] truncate">{{ $p->detail->pluck('buku.judul')->join(', ') }}</td>
                            <td class="px-4 py-3">{{ $p->user->name }}<span class="block text-xs text-gray-400">{{ $p->user->nim_nip }}</span></td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ optional($p->status === \App\Models\Peminjaman::DIKEMBALIKAN ? $p->tanggal_kembali : $p->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3"><x-status-badge :status="$p->status_tampil" /></td>
                            <td class="px-4 py-3">{{ optional($p->petugas)->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($p->status === \App\Models\Peminjaman::MENUNGGU)
                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">@csrf
                                            <input type="hidden" name="keputusan" value="setuju">
                                            <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-1 rounded">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">@csrf
                                            <input type="hidden" name="keputusan" value="tolak">
                                            <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-6 text-center text-gray-400 italic">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tugas & notifikasi --}}
    <div class="bg-white rounded-xl border border-t-2 border-t-gold-500 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">Tugas &amp; Notifikasi</h3>
        </div>
        <div class="flex gap-1 mb-3 text-xs" id="tabTugas">
            @foreach (['semua' => 'Semua', 'tugas' => 'Tugas', 'notifikasi' => 'Notifikasi'] as $k => $l)
                <button type="button" data-tab="{{ $k }}"
                    class="flex-1 py-1.5 rounded-lg {{ $k === 'semua' ? 'bg-gold-100 text-gold-700 font-medium' : 'bg-gray-50 text-gray-500' }}">{{ $l }}</button>
            @endforeach
        </div>
        <ul class="divide-y">
            @foreach ($tugas as $t)
                <li data-jenis="{{ $t['jenis'] }}">
                    <a href="{{ $t['url'] }}" class="flex items-start gap-3 py-3 hover:bg-gray-50 -mx-2 px-2 rounded-lg">
                        <div class="w-9 h-9 shrink-0 rounded-lg bg-gold-50 flex items-center justify-center">{{ $t['icon'] }}</div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-700">{{ $t['judul'] }}</p>
                            <p class="text-xs text-gray-500">{{ $t['isi'] }}</p>
                        </div>
                        @if ($t['aktif'])<span class="mt-1 w-2 h-2 rounded-full bg-red-500 shrink-0"></span>@endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    (function () {
        const data = @json($chart);
        const ctx = document.getElementById('grafikPeminjaman');
        let chart;

        function render(rentang) {
            const d = data[rentang];
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: d.labels,
                    datasets: [{
                        label: 'Peminjaman',
                        data: d.values,
                        borderColor: '#c99a12',
                        backgroundColor: 'rgba(232,185,35,0.18)',
                        fill: true, tension: 0.3, pointRadius: rentang === '7' ? 3 : 0,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { ticks: { maxTicksLimit: 7 } },
                    },
                },
            });
        }

        render('7');
        document.getElementById('rentangGrafik').addEventListener('change', e => render(e.target.value));

        document.querySelectorAll('#tabTugas button').forEach(btn => btn.addEventListener('click', () => {
            document.querySelectorAll('#tabTugas button').forEach(b => {
                const on = b === btn;
                b.classList.toggle('bg-gold-100', on); b.classList.toggle('text-gold-700', on);
                b.classList.toggle('font-medium', on); b.classList.toggle('bg-gray-50', !on);
                b.classList.toggle('text-gray-500', !on);
            });
            document.querySelectorAll('li[data-jenis]').forEach(li => {
                li.style.display = (btn.dataset.tab === 'semua' || li.dataset.jenis === btn.dataset.tab) ? '' : 'none';
            });
        }));
    })();
</script>
@endpush
