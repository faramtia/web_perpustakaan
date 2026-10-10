@extends('layouts.app')
@section('title', 'Pengembalian')
@section('page-title', 'Pengembalian')

@section('content')

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl">
            <i class="bi bi-arrow-repeat"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">Pengembalian Buku</h1>
            <p class="text-sm text-stone-500">Buku yang sedang dipinjam dan menunggu dikembalikan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full Ftext-sm">
            <thead class="text-stone-500 text-left border-b border-stone-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Peminjam</th>
                    <th class="px-6 py-4 font-semibold">Buku</th>
                    <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                    <th class="px-6 py-4 font-semibold">Jatuh Tempo</th>
                    <th class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($peminjaman as $p)
                    @php
                        $terlambat = $p->tanggal_jatuh_tempo && $p->tanggal_jatuh_tempo->isPast();
                        $nama = $p->user->nama ?? '-';
                    @endphp
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 shrink-0 rounded-full bg-yellow-100 text-yellow-800 grid place-items-center text-xs font-bold">
                                    {{ strtoupper(substr($nama, 0, 2)) }}
                                </div>
                                <div class="leading-tight">
                                    <p class="font-semibold text-stone-800">{{ $nama }}</p>
                                    <p class="text-xs text-stone-400 capitalize">{{ $p->user->role ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 max-w-xs">
                            <div class="flex items-center gap-2 text-stone-700">
                                <i class="bi bi-book text-stone-400"></i>
                                <span>{{ $p->detail->map(fn ($d) => $d->buku->judul ?? null)->filter()->join(', ') ?: '-' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-stone-600">
                            {{ optional($p->tanggal_pinjam)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="{{ $terlambat ? 'text-red-600 font-semibold' : 'text-stone-600' }}">
                                {{ optional($p->tanggal_jatuh_tempo)->format('d M Y') ?? '-' }}
                            </p>
                            @if ($terlambat)
                                <p class="text-xs text-red-500">Terlambat</p>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p) }}"
                                  onsubmit="return confirm('Tandai buku ini sudah dikembalikan?')">
                                @csrf
                                <button class="inline-flex items-center gap-1.5 text-xs font-semibold bg-stone-900 hover:bg-stone-700 text-white px-3.5 py-2 rounded-lg transition">
                                    <i class="bi bi-arrow-return-left"></i> Tandai Kembali
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 grid place-items-center text-2xl mb-3">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <p class="font-semibold text-stone-700">Tidak ada buku yang perlu dikembalikan</p>
                            <p class="text-sm text-stone-400">Peminjaman aktif akan muncul di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $peminjaman->links() }}</div>

@endsection