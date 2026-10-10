@extends('layouts.app')
@section('title', 'Reservasi')
@section('page-title', 'Reservasi')

@section('content')

    @php
        $role = auth()->user()->role;
        $petugas = in_array($role, ['admin', 'petugas']);
        $anggota = in_array($role, ['mahasiswa', 'dosen']);
    @endphp

    {{-- Pesan sukses / error --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-2 bg-green-50 ring-1 ring-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-5 flex items-center gap-2 bg-red-50 ring-1 ring-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">{{ $petugas ? 'Daftar Reservasi' : 'Reservasi Saya' }}</h1>
            <p class="text-sm text-stone-500">
                {{ $petugas ? 'Pantau reservasi buku yang diajukan anggota.' : 'Reservasi buku yang sedang tidak tersedia.' }}
            </p>
        </div>
    </div>

    {{-- Form reservasi (khusus anggota) --}}
    @if ($anggota)
        <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-5 mb-6">
            <h2 class="font-bold mb-1">Reservasi buku baru</h2>
            <p class="text-sm text-stone-500 mb-4">Buku yang stoknya habis bisa kamu reservasi, dan kamu akan diberi tahu saat tersedia.</p>

            @if ($bukuHabis->isEmpty())
                <p class="text-sm text-stone-400 italic">Saat ini semua buku tersedia, jadi tidak ada yang perlu direservasi.</p>
            @else
                <form method="POST" action="{{ route('anggota.reservasi.store') }}" class="flex flex-wrap gap-3">
                    @csrf
                    <select nama="buku_id" required
                            class="flex-1 min-w-[240px] bg-white border border-stone-200 rounded-xl px-4 py-2.5 text-sm
                                   focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                        <option value="">Pilih buku...</option>
                        @foreach ($bukuHabis as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                    <button class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-stone-900 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                        <i class="bi bi-plus-lg"></i> Reservasi
                    </button>
                </form>
            @endif
        </div>
    @endif

    {{-- Daftar --}}
    <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-stone-500 text-left border-b border-stone-200">
                <tr>
                    @if ($petugas)
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                    @endif
                    <th class="px-6 py-4 font-semibold">Buku</th>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    @if ($anggota)
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($reservasi as $r)
                    @php $nama = $r->user->nama ?? '-'; @endphp
                    <tr class="hover:bg-stone-50">

                        @if ($petugas)
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 shrink-0 rounded-full bg-yellow-100 text-yellow-800 grid place-items-center text-xs font-bold">
                                        {{ strtoupper(substr($nama, 0, 2)) }}
                                    </div>
                                    <div class="leading-tight">
                                        <p class="font-semibold text-stone-800">{{ $nama }}</p>
                                        <p class="text-xs text-stone-400 capitalize">{{ $r->user->role ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                        @endif
<<<<<<< HEAD

                        <td class="px-6 py-4 max-w-sm">
                            <div class="flex items-center gap-2 text-stone-700">
                                <i class="bi bi-book text-stone-400"></i>
                                <span>{{ $r->buku->judul ?? '-' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-stone-600">
                            {{ optional($r->tanggal_reservasi)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="px-6 py-4"><x-status-badge :status="$r->status" /></td>

                        @if ($anggota)
                            <td class="px-6 py-4">
                                @if ($r->status === 'menunggu')
                                    <form method="POST" action="{{ route('anggota.reservasi.batal', $r) }}"
                                          onsubmit="return confirm('Batalkan reservasi ini?')">
=======
                        <td class="px-5 py-3">{{ $r->buku->judul }}</td>
                        <td class="px-5 py-3">{{ optional($r->tanggal_reservasi)->format('d M Y') }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$r->status" /></td>
                        @if (in_array($role, ['mahasiswa','dosen']))
                            <td class="px-5 py-3">
                                @if ($r->status === 'Menunggu')
                                    <form method="POST" action="{{ route('anggota.reservasi.batal', $r) }}">
>>>>>>> 5599154d47bfc8afee700f5d1a47068063fd88e3
                                        @csrf
                                        <button class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 px-3.5 py-2 rounded-lg transition">
                                            <i class="bi bi-x-lg"></i> Batalkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-stone-300">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 grid place-items-center text-2xl mb-3">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <p class="font-semibold text-stone-700">Belum ada reservasi</p>
                            <p class="text-sm text-stone-400">Reservasi buku akan muncul di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $reservasi->links() }}</div>

@endsection