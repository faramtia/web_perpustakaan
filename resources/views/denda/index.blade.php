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
        <form method="POST" action="{{ route('petugas.denda.store') }}" class="card mb-6 grid grid-cols-1 gap-3 p-5 md:grid-cols-5">
            @csrf
            <h2 class="font-bold md:col-span-5">Catat denda buku rusak / hilang</h2>
            <select name="peminjaman_id" required class="input md:col-span-2">
                <option value="">Pilih transaksi...</option>
                @foreach ($transaksi as $t)
                    <option value="{{ $t->id }}" @selected(old('peminjaman_id') == $t->id)>#{{ $t->id }} · {{ $t->user->nama ?? '-' }} · {{ \Illuminate\Support\Str::limit($t->judul_buku, 40) }}</option>
                @endforeach
            </select>
            <select name="jenis" required class="input">
                <option value="rusak" @selected(old('jenis') === 'rusak')>Rusak</option>
                <option value="hilang" @selected(old('jenis') === 'hilang')>Hilang</option>
            </select>
            <input type="number" name="jumlah_denda" value="{{ old('jumlah_denda') }}" min="1000" step="500" required placeholder="Nominal (Rp)" class="input">
            <input type="text" name="keterangan" value="{{ old('keterangan') }}" maxlength="255" placeholder="Keterangan" class="input md:col-span-4">
            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat</button>
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
                        <td class="max-w-xs truncate">{{ $d->peminjaman?->judul_buku ?? '-' }}</td>
                        <td><span class="chip bg-stone-100 text-stone-600 capitalize">{{ $d->jenis }}</span></td>
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
@endsection
