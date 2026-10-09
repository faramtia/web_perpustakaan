@extends('layouts.app')
@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman')

@section('content')

    @php $role = auth()->user()->role; @endphp

    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-gray-700">{{ in_array($role, ['admin','petugas']) ? 'Semua Transaksi Peminjaman' : 'Peminjaman Saya' }}</h2>
        @if (in_array($role, ['mahasiswa', 'dosen']))
            <a href="{{ route('anggota.peminjaman.create') }}" class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                + Ajukan Pinjam
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Peminjam</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Buku</th>
                    <th class="px-5 py-3 font-medium">Tgl Pinjam</th>
                    <th class="px-5 py-3 font-medium">Jatuh Tempo</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Denda</th>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($peminjaman as $p)
                    <tr>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">{{ $p->user->name }} <span class="text-xs text-gray-400 capitalize">({{ $p->user->role }})</span></td>
                        @endif
                        <td class="px-5 py-3">{{ $p->detail->pluck('buku.judul')->join(', ') }}</td>
                        <td class="px-5 py-3">{{ optional($p->tanggal_pinjam)->format('d M Y') ?? '-' }}</td>
                        <td class="px-5 py-3">{{ optional($p->tanggal_jatuh_tempo)->format('d M Y') ?? '-' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$p->status_tampil" /></td>
                        <td class="px-5 py-3">
                            @php $totalDenda = $p->denda->sum('jumlah_denda'); @endphp
                            {{ $totalDenda > 0 ? 'Rp '.number_format($totalDenda, 0, ',', '.') : '-' }}
                        </td>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3 flex gap-2">
                                @if ($p->status === 'Menunggu')
                                    <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                        @csrf
                                        <input type="hidden" name="keputusan" value="setuju">
                                        <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('petugas.peminjaman.verifikasi', $p) }}">
                                        @csrf
                                        <input type="hidden" name="keputusan" value="tolak">
                                        <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                                    </form>
                                @elseif ($p->status === 'Dipinjam')
                                    <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p) }}">
                                        @csrf
                                        <button class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg">Tandai Kembali</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-6 text-center text-gray-400 italic">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $peminjaman->links() }}</div>

@endsection
