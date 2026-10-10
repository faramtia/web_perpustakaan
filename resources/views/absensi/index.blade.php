@extends('layouts.app')
@section('title', 'Absensi')
@section('page-title', 'Absensi')

@section('content')

    @php
        $role = auth()->user()->role;
        $petugas = in_array($role, ['admin', 'petugas']);
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
            <i class="bi bi-person-check"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">{{ $petugas ? 'Absensi Pengunjung' : 'Absensi Saya' }}</h1>
            <p class="text-sm text-stone-500">
                {{ $petugas ? 'Catat dan pantau kehadiran anggota di perpustakaan.' : 'Riwayat kehadiranmu di perpustakaan.' }}
            </p>
        </div>
    </div>

    {{-- Input absensi manual (khusus petugas) --}}
    @if ($petugas)
        <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-5 mb-6">
            <h2 class="font-bold mb-1">Input absensi manual</h2>
            <p class="text-sm text-stone-500 mb-4">Masukkan ID user anggota, lalu klik Catat Absensi.</p>

            <form method="POST" action="{{ route('petugas.absensi.store') }}" class="flex flex-wrap gap-3">
                @csrf
                <div class="relative">
                    <i class="bi bi-person-badge absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                    <input type="number" name="user_id" value="{{ old('user_id') }}" placeholder="ID user anggota" required
                           class="w-64 bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </div>
                <button class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-stone-900 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                    <i class="bi bi-check-lg"></i> Catat Absensi
                </button>
            </form>

            <p class="text-xs text-stone-400 mt-3">Versi awal masih input manual ID user. Nanti bisa diganti scan QR kartu anggota.</p>
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
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Waktu Masuk</th>
                    <th class="px-6 py-4 font-semibold">Metode</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($absensi as $a)
                    @php
                        $nama = $a->user->nama ?? '-';
                        $metode = $a->metode ?? null;
                    @endphp
                    <tr class="hover:bg-stone-50">

                        @if ($petugas)
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 shrink-0 rounded-full bg-yellow-100 text-yellow-800 grid place-items-center text-xs font-bold">
                                        {{ strtoupper(substr($nama, 0, 2)) }}
                                    </div>
                                    <div class="leading-tight">
                                        <p class="font-semibold text-stone-800">{{ $nama }}</p>
                                        <p class="text-xs text-stone-400 capitalize">{{ $a->user->role ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                        @endif

                        <td class="px-6 py-4 whitespace-nowrap text-stone-700">
                            {{ $a->tanggal ? \Illuminate\Support\Carbon::parse($a->tanggal)->format('d M Y') : '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-stone-600">
                            <i class="bi bi-clock text-stone-400"></i>
                            {{ $a->waktu_masuk ? substr($a->waktu_masuk, 0, 5) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($metode)
                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold capitalize
                                             {{ $metode === 'manual' ? 'bg-stone-100 text-stone-600' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $metode }}
                                </span>
                            @else
                                <span class="text-stone-300">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-14 text-center">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 grid place-items-center text-2xl mb-3">
                                <i class="bi bi-person-x"></i>
                            </div>
                            <p class="font-semibold text-stone-700">Belum ada data absensi</p>
                            <p class="text-sm text-stone-400">Kehadiran anggota akan muncul di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $absensi->links() }}</div>

@endsection