@extends('layouts.app')
@section('title', 'Denda Perpustakaan')
@section('page-title', 'Denda Perpustakaan')

@section('content')

    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400 text-stone-900 grid place-items-center text-xl">
            <i class="bi bi-coin"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold leading-tight">Denda</h1>
            <p class="text-sm text-stone-500">Pantau dan kelola data peminjaman perpustakaan.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl px-4 py-3 mb-6 text-sm flex items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Ringkasan Statistik Aman (Tanpa Akses Kolom Denda) --}}
    <div class="grid sm:grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl grid place-items-center text-xl bg-yellow-100 text-yellow-700">
                <i class="bi bi-coin"></i>
            </div>
            <div>
                <p class="text-xs text-stone-500">Total Data Peminjaman</p>
                <p class="text-xl font-extrabold">{{ method_exists($dendaList, 'total') ? $dendaList->total() : count($dendaList) }}</p>
            </div>
        </div>
    </div>

    {{-- Tabel Data Peminjaman / Denda --}}
    <div class="bg-white rounded-2xl ring-1 ring-stone-200 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-stone-500 text-left border-b border-stone-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Anggota</th>
                    <th class="px-6 py-4 font-semibold">Tanggal Pinjam</th>
                    <th class="px-6 py-4 font-semibold">Status Peminjaman</th>
                    @if (in_array(auth()->user()->role ?? '', ['admin', 'petugas'], true))
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($dendaList as $d)
                    @php
                        $nama = $d->user->name ?? $d->user->nama ?? 'Tidak Diketahui';
                        $tglPinjam = $d->tgl_pinjam ?? $d->tanggal_pinjam ?? $d->created_at ?? '-';
                        $status = $d->status ?? 'Aktif';
                    @endphp
                    <tr class="hover:bg-stone-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-yellow-100 text-yellow-800 grid place-items-center text-xs font-bold">
                                    {{ strtoupper(substr($nama, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-stone-800 block">{{ $nama }}</span>
                                    <span class="text-xs text-stone-400">{{ $d->user->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-stone-700">{{ $tglPinjam }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        @if (in_array(auth()->user()->role ?? '', ['admin', 'petugas'], true))
                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ url('/denda/' . $d->id . '/bayar') }}" onsubmit="return confirm('Perbarui status peminjaman ini?')"> 
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                        Selesai / Lunas
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-stone-400 italic">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if (method_exists($dendaList, 'links'))
        <div class="mt-4">
            {{ $dendaList->links() }}
        </div>
    @endif

@endsection