@extends('layouts.app')
@section('title', 'Reservasi')

@section('content')
@php $staff = auth()->user()->isStaff(); @endphp

    <x-page-header icon="bi-calendar-check" :title="$staff ? 'Daftar Reservasi' : 'Reservasi Saya'"
        :subtitle="$staff ? 'Proses reservasi buku yang diajukan anggota.' : 'Reservasi untuk buku yang stoknya sedang habis.'" />

    @if (! $staff && ($notifReservasi ?? collect())->isNotEmpty())
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
            <p class="mb-2 font-bold text-emerald-800"><i class="bi bi-bell-fill mr-1"></i> Buku reservasimu sudah tersedia!</p>
            <ul class="space-y-1 text-sm text-emerald-800">
                @foreach ($notifReservasi as $n)
                    <li><i class="bi bi-book mr-1"></i><strong>{{ $n->buku->judul }}</strong> &mdash; {{ $n->buku->stok }} eksemplar tersedia. Segera datang ke perpustakaan atau ajukan peminjaman.</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $staff)
        <div class="card mb-6 p-5">
            <h2 class="mb-1 font-bold">Reservasi buku baru</h2>
            <p class="mb-4 text-sm text-stone-500">Buku yang stoknya habis bisa kamu reservasi. Petugas akan memprosesnya saat buku kembali.</p>

            @if ($bukuHabis->isEmpty())
                <p class="text-sm italic text-stone-400">Saat ini semua buku tersedia, jadi tidak ada yang perlu direservasi.</p>
            @else
                <form method="POST" action="{{ route('anggota.reservasi.store') }}" class="flex flex-wrap gap-3">
                    @csrf
                    <select name="buku_id" required class="input min-w-[240px] flex-1">
                        <option value="">Pilih buku...</option>
                        @foreach ($bukuHabis as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Reservasi</button>
                </form>
            @endif
        </div>
    @endif

    @if ($staff)
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-stat-card label="Menunggu" :value="$ringkasan['Menunggu'] ?? 0" icon="bi-hourglass-split" tone="yellow" />
            <x-stat-card label="Diproses" :value="$ringkasan['Diproses'] ?? 0" icon="bi-arrow-repeat" tone="blue" />
            <x-stat-card label="Selesai" :value="$ringkasan['Selesai'] ?? 0" icon="bi-check-circle" tone="green" />
            <x-stat-card label="Dibatalkan" :value="$ringkasan['Dibatalkan'] ?? 0" icon="bi-x-circle" tone="red" />
        </div>

        <form method="GET" class="mb-4 flex gap-2">
            @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari anggota, NIM/NIP, atau judul buku..." class="input max-w-sm">
            <button class="btn btn-primary">Cari</button>
        </form>
    @endif

    <x-status-tabs :options="['Menunggu' => 'Menunggu', 'Diproses' => 'Diproses', 'Selesai' => 'Selesai', 'Dibatalkan' => 'Dibatalkan']" />

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    @if ($staff)<th>Anggota</th>@endif
                    <th>Buku</th><th>Tanggal</th><th>Stok kini</th><th>Status</th><th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservasi as $r)
                    <tr>
                        @if ($staff)<td><x-user-cell :user="$r->user" /></td>@endif
                        <td><i class="bi bi-book mr-1 text-stone-400"></i>{{ $r->buku->judul ?? '-' }}</td>
                        <td class="whitespace-nowrap">{{ optional($r->tanggal_reservasi)->format('d M Y') ?? '-' }}</td>
                        <td>
                            @php $stok = (int) ($r->buku->stok ?? 0); @endphp
                            <span class="chip {{ $stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">{{ $stok > 0 ? $stok.' tersedia' : 'habis' }}</span>
                        </td>
                        <td>
                            <x-status-badge :status="$r->status" />
                            @if (! $staff && $stok > 0 && in_array($r->status, ['Menunggu', 'Diproses']))
                                <span class="chip ml-1 bg-emerald-100 text-emerald-700"><i class="bi bi-bell-fill"></i> Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-2">
                                @if ($staff && in_array($r->status, ['Menunggu', 'Diproses']))
                                    @if ($r->status === 'Menunggu')
                                        <form method="POST" action="{{ route('petugas.reservasi.status', $r) }}">
                                            @csrf <input type="hidden" name="status" value="Diproses">
                                            <button class="btn btn-sm btn-dark disabled:cursor-not-allowed disabled:opacity-40"
                                                    @disabled($stok < 1) title="{{ $stok < 1 ? 'Stok masih kosong' : 'Buku siap diambil anggota' }}">Proses</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('petugas.reservasi.status', $r) }}">
                                            @csrf <input type="hidden" name="status" value="Selesai">
                                            <button class="btn btn-sm btn-success">Selesai</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('petugas.reservasi.status', $r) }}" onsubmit="return confirm('Batalkan reservasi ini?')">
                                        @csrf <input type="hidden" name="status" value="Dibatalkan">
                                        <button class="btn btn-sm btn-danger">Batalkan</button>
                                    </form>
                                @elseif (! $staff && $r->status === 'Menunggu')
                                    <form method="POST" action="{{ route('anggota.reservasi.batal', $r) }}" onsubmit="return confirm('Batalkan reservasi ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i> Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-stone-300">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state :cols="$staff ? 6 : 5" icon="bi-calendar-x" title="Belum ada reservasi" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reservasi->links() }}</div>
@endsection
