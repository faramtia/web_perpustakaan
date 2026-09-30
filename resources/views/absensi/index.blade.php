@extends('layouts.app')
@section('title', 'Absensi')
@section('page-title', 'Absensi')

@section('content')

    @php $role = auth()->user()->role; @endphp

    @if (in_array($role, ['admin', 'petugas']))
        <div class="bg-white border rounded-xl p-5 mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Input Absensi Manual</h3>
            <form method="POST" action="{{ route('petugas.absensi.store') }}" class="flex gap-2">
                @csrf
                <input type="number" name="user_id" required placeholder="ID user anggota"
                       class="border rounded-lg px-4 py-2.5 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-gold-400">
                <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                    Catat Hadir
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-2">Versi awal masih input manual ID user — nanti bisa diganti scan QR kartu anggota.</p>
        </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Anggota</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Waktu Masuk</th>
                    <th class="px-5 py-3 font-medium">Metode</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($absensi as $a)
                    <tr>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">{{ $a->user->name }}</td>
                        @endif
                        <td class="px-5 py-3">{{ optional($a->tanggal)->format('d M Y') }}</td>
                        <td class="px-5 py-3">{{ $a->waktu_masuk }}</td>
                        <td class="px-5 py-3 capitalize">{{ $a->metode }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-6 text-center text-gray-400 italic">Belum ada catatan absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $absensi->links() }}</div>

@endsection
