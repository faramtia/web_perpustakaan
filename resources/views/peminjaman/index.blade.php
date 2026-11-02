@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
@php
    $user = auth()->user();
    $staff = $user->isStaff();
    $belum = request('tampil') === 'belum';
@endphp

    <x-page-header :icon="$belum ? 'bi-arrow-repeat' : 'bi-box-arrow-up-right'"
        :title="$belum ? 'Pengembalian Buku' : ($staff ? 'Transaksi Peminjaman' : 'Peminjaman Saya')"
        :subtitle="$belum ? 'Buku yang masih dipinjam dan belum dikembalikan.' : ($staff ? 'Verifikasi pengajuan dan pantau buku yang sedang dipinjam.' : 'Riwayat dan status peminjamanmu.')">
        @if (! $staff)
            <a href="{{ route('anggota.peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajukan Peminjaman</a>
        @endif
    </x-page-header>

    @if (! $belum)
        <x-status-tabs :options="['Menunggu' => 'Menunggu', 'Dipinjam' => 'Dipinjam', 'Terlambat' => 'Terlambat', 'Dikembalikan' => 'Dikembalikan', 'Ditolak' => 'Ditolak']" />
    @endif

    @if ($staff)
        <form method="GET" class="mb-4 flex gap-2">
            @if ($belum)<input type="hidden" name="tampil" value="belum">@endif
            @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="relative w-full max-w-sm">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / NIM peminjam..." class="input pl-10">
            </div>
            <button class="btn btn-primary">Cari</button>
        </form>
    @endif

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    @if ($staff)<th>Peminjam</th>@endif
                    <th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Denda</th>
                    @if ($staff)<th class="text-right">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $p)
                    @php $totalDenda = $p->denda->sum('jumlah_denda'); @endphp
                    <tr>
                        @if ($staff)<td><x-user-cell :user="$p->user" /></td>@endif
                        <td class="max-w-xs">
                            <i class="bi bi-book mr-1 text-stone-400"></i>{{ $p->judul_buku }}
                            @if ($p->petugas)<p class="text-xs text-stone-400">Diproses: {{ $p->petugas->nama }}</p>@endif
                        </td>
                        <td class="whitespace-nowrap">{{ optional($p->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                        <td class="whitespace-nowrap">
                            {{ optional($p->tanggal_jatuh_tempo)->format('d M Y') ?? '-' }}
                            @if ($p->tanggal_kembali)<p class="text-xs text-stone-400">Kembali {{ $p->tanggal_kembali->format('d M Y') }}</p>@endif
                        </td>
                        <td><x-status-badge :status="$p->status_tampil" /></td>
                        <td class="whitespace-nowrap">{{ $totalDenda > 0 ? 'Rp '.number_format($totalDenda, 0, ',', '.') : '-' }}</td>
                        @if ($staff)
                            <td>
                                <div class="flex justify-end gap-2">
                                    @if ($p->status === 'Menunggu')
                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                            @csrf <input type="hidden" name="keputusan" value="setuju">
                                            <button class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}" onsubmit="return confirm('Tolak pengajuan ini?')">
                                            @csrf <input type="hidden" name="keputusan" value="tolak">
                                            <button class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    @elseif ($p->status === 'Dipinjam')
                                        <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p) }}" onsubmit="return confirm('Tandai buku ini sudah dikembalikan?')">
                                            @csrf
                                            <button class="btn btn-sm btn-dark"><i class="bi bi-arrow-return-left"></i> Tandai Kembali</button>
                                        </form>
                                    @else
                                        <span class="text-stone-300">-</span>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <x-empty-state :cols="$staff ? 7 : 5" icon="bi-box-arrow-up-right" title="Belum ada transaksi" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $peminjaman->links() }}</div>
@endsection
