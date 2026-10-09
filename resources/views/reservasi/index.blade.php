@extends('layouts.app')
@section('title', 'Reservasi')
@section('page-title', 'Reservasi')

@section('content')

    @php $role = auth()->user()->role; @endphp

    @if (in_array($role, ['mahasiswa', 'dosen']))
        <div class="bg-white border rounded-xl p-5 mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Buat Reservasi Baru</h3>
            <form method="POST" action="{{ route('anggota.reservasi.store') }}" class="flex gap-2">
                @csrf
                <select name="buku_id" required class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <option value="">-- Pilih buku yang stoknya kosong --</option>
                    @foreach ($bukuHabis as $b)
                        <option value="{{ $b->id }}">{{ $b->judul }}</option>
                    @endforeach
                </select>
                <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg whitespace-nowrap">
                    Reservasi
                </button>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Anggota</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Buku</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    @if (in_array($role, ['mahasiswa','dosen']))
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($reservasi as $r)
                    <tr>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">{{ $r->user->name }}</td>
                        @endif
                        <td class="px-5 py-3">{{ $r->buku->judul }}</td>
                        <td class="px-5 py-3">{{ optional($r->tanggal_reservasi)->format('d M Y') }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$r->status" /></td>
                        @if (in_array($role, ['mahasiswa','dosen']))
                            <td class="px-5 py-3">
                                @if ($r->status === 'Menunggu')
                                    <form method="POST" action="{{ route('anggota.reservasi.batal', $r) }}">
                                        @csrf
                                        <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400 italic">Belum ada reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reservasi->links() }}</div>

@endsection
