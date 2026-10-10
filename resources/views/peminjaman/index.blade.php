@extends('layouts.app')
@section('title', 'Transaksi Peminjaman')
@section('page-title', 'Transaksi Peminjaman')

@section('content')

    @php
        $role    = auth()->user()->role ?? '';
        $isStaff = in_array($role, ['admin', 'petugas'], true);
        $belum   = request('tampil') === 'belum';

        $statusLabel = [
            'pending'      => ['Menunggu',     'bg-yellow-100 text-yellow-700'],
            'menunggu'     => ['Menunggu',     'bg-yellow-100 text-yellow-700'],
            'aktif'        => ['Dipinjam',     'bg-blue-100 text-blue-700'],
            'dipinjam'     => ['Dipinjam',     'bg-blue-100 text-blue-700'],
            'terlambat'    => ['Terlambat',    'bg-red-100 text-red-600'],
            'dikembalikan' => ['Dikembalikan', 'bg-gray-100 text-gray-600'],
            'ditolak'      => ['Ditolak',      'bg-red-50 text-red-500'],
        ];
    @endphp

    {{-- Judul halaman --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-yellow-400 flex items-center justify-center text-2xl text-white">
            <i class="bi {{ $belum ? 'bi-arrow-repeat' : 'bi-box-arrow-up-right' }}"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold">{{ $belum ? 'Pengembalian Buku' : 'Transaksi Peminjaman' }}</h1>
            <p class="text-sm text-gray-500">
                {{ $belum ? 'Daftar buku yang masih dipinjam dan belum dikembalikan.' : 'Verifikasi pengajuan dan pantau buku yang sedang dipinjam.' }}
            </p>
        </div>
    </div>

    {{-- Pesan sukses / error --}}
    @if (session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tombol ajukan (khusus mahasiswa/dosen) --}}
    @if (in_array($role, ['mahasiswa', 'dosen'], true))
        <div class="mb-4">
            <a href="{{ route('anggota.peminjaman.create') }}"
               class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                <i class="bi bi-plus-lg"></i> Ajukan Peminjaman
            </a>
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-gray-600 text-left border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Peminjam</th>
                    <th class="px-6 py-4 font-semibold">Buku</th>
                    <th class="px-6 py-4 font-semibold">Tgl Pinjam</th>
                    <th class="px-6 py-4 font-semibold">Jatuh Tempo</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    @if ($isStaff)
                        <th class="px-6 py-4 font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($peminjaman as $p)
                    @php
                        $nama = $p->user->name ?? $p->user->nama ?? '-';
                        $inisial = collect(explode(' ', $nama))
                            ->filter()->take(2)
                            ->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))
                            ->implode('');

                        $judulBuku = $p->detail
                            ->map(fn ($d) => $d->buku->judul ?? null)
                            ->filter()
                            ->implode(', ');

                        $tglPinjam = $p->tanggal_pinjam
                            ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y')
                            : '-';
                        $tglTempo = $p->tanggal_jatuh_tempo
                            ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y')
                            : '-';

                        $statusKey = strtolower(trim($p->status ?? ''));
                        [$labelStatus, $kelasStatus] = $statusLabel[$statusKey] ?? [ucfirst($p->status ?? '-'), 'bg-gray-100 text-gray-600'];

                        $menunggu = str_contains($statusKey, 'pending') || str_contains($statusKey, 'tunggu') || str_contains($statusKey, 'ajuk');
                        $aktif    = ! $menunggu
                                    && ! str_contains($statusKey, 'kembali')
                                    && ! str_contains($statusKey, 'tolak')
                                    && (str_contains($statusKey, 'pinjam') || str_contains($statusKey, 'aktif'));
                    @endphp

                    <tr class="hover:bg-yellow-50/40">
                        {{-- Peminjam --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full bg-yellow-100 text-yellow-700 font-bold flex items-center justify-center text-sm">
                                    {{ $inisial ?: '?' }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $nama }}</p>
                                    <p class="text-xs text-gray-400">{{ ucfirst($p->user->role ?? '') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Buku --}}
                        <td class="px-6 py-4 text-gray-700">
                            <i class="bi bi-book text-gray-400 mr-1"></i>
                            {{ $judulBuku !== '' ? $judulBuku : '-' }}
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-4 text-gray-700">{{ $tglPinjam }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $tglTempo }}</td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $kelasStatus }}">
                                {{ $labelStatus }}
                            </span>
                        </td>

                        {{-- Aksi (admin & petugas) --}}
                        @if ($isStaff)
                            <td class="px-6 py-4">
                                @if ($menunggu)
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p->id) }}">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="setuju">
                                            <button type="submit"
                                                    class="text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">
                                                Setujui
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p->id) }}"
                                              onsubmit="return confirm('Tolak pengajuan ini?')">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="tolak">
                                            <button type="submit"
                                                    class="text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>

                                @elseif ($aktif)
                                    <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p->id) }}" onsubmit="return confirm('Tandai buku ini sudah dikembalikan?')">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg">
                                            Kembalikan
                                        </button>
                                    </form>

                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isStaff ? 6 : 5 }}" class="px-6 py-10 text-center text-gray-400 italic">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $peminjaman->links() }}</div>

@endsection