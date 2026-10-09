@extends('layouts.app')
@section('title', 'Tugas Akhir')
@section('page-title', 'Tugas Akhir')

@section('content')

    @php $role = auth()->user()->role; @endphp

    @if (in_array($role, ['mahasiswa', 'dosen']))
        <div class="bg-white border rounded-xl p-5 mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Upload Tugas Akhir</h3>
            <form method="POST" action="{{ route('anggota.tugas-akhir.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="text" name="judul" required placeholder="Judul tugas akhir"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                <input type="file" name="file" accept="application/pdf" required
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                <p class="text-xs text-gray-400">File PDF, maksimal 10MB.</p>
                <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                    Upload
                </button>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Mahasiswa/Dosen</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Judul</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Catatan</th>
                    @if (in_array($role, ['admin','petugas']))
                        <th class="px-5 py-3 font-medium">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($tugasAkhir as $ta)
                    <tr>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">{{ $ta->user->name }}</td>
                        @endif
                        <td class="px-5 py-3">{{ $ta->judul }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$ta->status" /></td>
                        <td class="px-5 py-3 text-gray-500">{{ $ta->catatan_reviewer ?? '-' }}</td>
                        @if (in_array($role, ['admin','petugas']))
                            <td class="px-5 py-3">
                                @if ($ta->status === 'Menunggu')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('petugas.tugas-akhir.review', $ta) }}">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="Disetujui">
                                            <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('petugas.tugas-akhir.review', $ta) }}">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="Ditolak">
                                            <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400 italic">Belum ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tugasAkhir->links() }}</div>

@endsection
