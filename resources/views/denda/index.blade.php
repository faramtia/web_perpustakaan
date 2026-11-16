@extends('layouts.app')
@section('title', 'Denda')

@section('content')
@php $staff = auth()->user()->isStaff(); $rp = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.'); @endphp

    <x-page-header icon="bi-coin" :title="$staff ? 'Denda Perpustakaan' : 'Denda Saya'"
        :subtitle="$staff ? 'Denda keterlambatan tercatat otomatis saat pengembalian. Catat juga buku rusak atau hilang.' : 'Denda bayar langsung di meja petugas perpustakaan.'" />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Belum Lunas" :value="$rp($totalBelumLunas)" :hint="$jumlahBelumLunas.' denda'" icon="bi-exclamation-circle" tone="red" />
        <x-stat-card label="Denda Berjalan" :value="$rp($totalBerjalan)" :hint="$berjalan->count().' buku terlambat'" icon="bi-hourglass-split" tone="blue" />
        <x-stat-card label="Sudah Lunas" :value="$rp($totalLunas)" icon="bi-check-circle" tone="green" />
        <x-stat-card label="Total Denda" :value="$rp($totalBelumLunas + $totalLunas)" icon="bi-coin" tone="yellow" />
    </div>

    @if ($berjalan->isNotEmpty())
        <div class="card mb-6 overflow-hidden">
            <div class="border-b border-stone-100 px-5 py-3">
                <h2 class="font-bold">Sedang terlambat (belum dikembalikan)</h2>
                <p class="text-xs text-stone-500">Denda dihitung saat buku dikembalikan. Angka di bawah adalah estimasi sampai hari ini.</p>
            </div>
            <div class="table-wrap !rounded-none !border-0">
                <table class="tbl">
                    <thead>
                        <tr>
                            @if ($staff)<th>Anggota</th>@endif
                            <th>Buku</th><th>Jatuh tempo</th><th>Terlambat</th><th>Estimasi denda</th>
                            @if ($staff)<th class="text-right">Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($berjalan as $p)
                            <tr>
                                @if ($staff)<td><x-user-cell :user="$p->user" /></td>@endif
                                <td class="max-w-xs truncate">{{ $p->judul_buku }}</td>
                                <td class="whitespace-nowrap">{{ $p->tanggal_jatuh_tempo->format('d M Y') }}</td>
                                <td><span class="chip bg-red-100 text-red-600">{{ $p->hari_telat }} hari</span></td>
                                <td class="whitespace-nowrap font-semibold">{{ $rp($p->estimasi) }}</td>
                                @if ($staff)
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p) }}" onsubmit="return confirm('Tandai buku ini sudah dikembalikan? Denda akan dicatat otomatis.')">
                                            @csrf
                                            <button class="btn btn-sm btn-dark">Kembalikan</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($staff)
        <div class="mb-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
            {{-- Form catat denda rusak / hilang --}}
            <form method="POST" action="{{ route('petugas.denda.store') }}" class="card grid grid-cols-1 gap-3 p-5 sm:grid-cols-2 lg:col-span-2">
                @csrf
                <div class="sm:col-span-2">
                    <h2 class="font-bold">Catat denda buku rusak / hilang</h2>
                    <p class="text-xs text-stone-500">Pilih buku yang bermasalah dan tingkat kerusakannya. Nominal terisi otomatis sesuai ketentuan, dan boleh diubah.</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="label">Buku yang rusak / hilang</label>
                    <select name="detail_peminjaman_id" required class="input">
                        <option value="">Pilih buku (dari riwayat peminjaman)...</option>
                        @foreach ($daftarBuku as $d)
                            <option value="{{ $d->id }}" @selected(old('detail_peminjaman_id') == $d->id)>
                                #{{ $d->peminjaman_id }} · {{ $d->peminjaman->user->nama ?? '-' }} · {{ \Illuminate\Support\Str::limit($d->buku->judul ?? '-', 45) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Tingkat kerusakan</label>
                    <select name="tingkat" id="tingkat" required class="input">
                        <option value="">Pilih tingkat...</option>
                        @foreach ($ketentuan as $kode => $k)
                            <option value="{{ $kode }}" data-nominal="{{ $k['nominal'] }}" @selected(old('tingkat') === $kode)>{{ $k['label'] }} (Rp {{ number_format($k['nominal'], 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Nominal denda (Rp)</label>
                    <input type="number" name="jumlah_denda" id="nominal" value="{{ old('jumlah_denda') }}" min="1000" step="500" required class="input">
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Keterangan <span class="font-normal text-stone-400">(opsional)</span></label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" maxlength="200" placeholder="Contoh: halaman 20-35 sobek" class="input">
                </div>
                <div class="sm:col-span-2"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Denda</button></div>
            </form>

            {{-- Ketentuan denda --}}
            <div class="card p-5">
                <h2 class="mb-3 font-bold">Ketentuan denda</h2>
                <ul class="space-y-3 text-sm">
                    <li>
                        <p class="font-semibold text-stone-800">Terlambat</p>
                        <p class="text-xs text-stone-500">Rp1.000 per hari, dihitung otomatis saat buku dikembalikan.</p>
                    </li>
                    @foreach ($ketentuan as $k)
                        <li>
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-semibold text-stone-800">{{ $k['label'] }}</p>
                                <span class="chip bg-yellow-100 text-yellow-700">Rp {{ number_format($k['nominal'], 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-stone-500">{{ $k['contoh'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <form method="GET" class="card mb-4 flex flex-wrap items-center gap-3 p-4">
            @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="relative min-w-[220px] flex-1">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM/NIP, atau judul buku..." class="input pl-10">
            </div>
            <select name="jenis" class="input max-w-[11rem]">
                <option value="">Semua jenis</option>
                @foreach (['terlambat' => 'Terlambat', 'rusak' => 'Rusak', 'hilang' => 'Hilang'] as $v => $l)
                    <option value="{{ $v }}" @selected(request('jenis') === $v)>{{ $l }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">Cari</button>
            @if (request()->hasAny(['q', 'jenis']))<a href="{{ route('petugas.denda.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-soft">Reset</a>@endif
            <a href="{{ route('petugas.laporan.show', 'denda') }}" class="btn btn-soft ml-auto"><i class="bi bi-file-earmark-text"></i> Laporan Denda</a>
        </form>
    @endif

    <x-status-tabs :options="['Belum Lunas' => 'Belum Lunas', 'Lunas' => 'Lunas']" />

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    @if ($staff)<th>Anggota</th>@endif
                    <th>Buku</th><th>Jenis</th><th>Keterangan</th><th>Denda</th><th>Status</th>
                    @if ($staff)<th class="text-right">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($denda as $d)
                    <tr>
                        @if ($staff)<td><x-user-cell :user="$d->peminjaman->user" /></td>@endif
                        <td class="max-w-xs"><p class="truncate font-medium text-stone-800">{{ $d->detail?->buku?->judul ?? $d->peminjaman?->judul_buku ?? '-' }}</p><p class="text-xs text-stone-400">Transaksi #{{ $d->peminjaman_id }}</p></td>
                        <td>
                            <span class="chip capitalize {{ ['terlambat' => 'bg-orange-100 text-orange-700', 'rusak' => 'bg-amber-100 text-amber-700', 'hilang' => 'bg-red-100 text-red-600'][$d->jenis] ?? 'bg-stone-100 text-stone-600' }}">{{ $d->jenis }}</span>
                            @if ($d->hari_terlambat > 0)<p class="mt-1 text-xs text-stone-400">{{ $d->hari_terlambat }} hari × Rp {{ number_format($d->tarif_per_hari, 0, ',', '.') }}</p>@endif
                        </td>
                        <td class="text-stone-500">{{ $d->keterangan ?: '-' }}</td>
                        <td class="whitespace-nowrap font-semibold">{{ $rp($d->jumlah_denda) }}</td>
                        <td>
                            <x-status-badge :status="$d->status_bayar" />
                            @if ($d->tanggal_bayar)<p class="text-xs text-stone-400">{{ $d->tanggal_bayar->format('d M Y') }}</p>@endif
                        </td>
                        @if ($staff)
                            <td class="text-right">
                                @if ($d->status_bayar === 'Belum Lunas')
                                    <form method="POST" action="{{ route('petugas.denda.bayar', $d) }}" onsubmit="return confirm('Catat denda ini sudah dibayar?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Tandai Lunas</button>
                                    </form>
                                @else
                                    <span class="text-stone-300">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <x-empty-state :cols="$staff ? 7 : 5" icon="bi-emoji-smile" title="Tidak ada denda" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $denda->links() }}</div>

@push('scripts')
<script>
    // Isi nominal otomatis sesuai ketentuan tingkat kerusakan (masih bisa diubah).
    document.getElementById('tingkat')?.addEventListener('change', function () {
        const nominal = this.selectedOptions[0]?.dataset.nominal;
        if (nominal) document.getElementById('nominal').value = nominal;
    });
</script>
@endpush
@endsection
