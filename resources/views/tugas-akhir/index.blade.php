@extends('layouts.app')
@section('title', 'Tugas Akhir')

@section('content')
@php $staff = auth()->user()->isStaff(); @endphp

    <x-page-header icon="bi-mortarboard" :title="$staff ? 'Kelola Tugas Akhir' : 'Tugas Akhir Saya'"
        :subtitle="$staff ? 'Masukkan tugas akhir anggota ke repository dan tinjau yang masih menunggu.' : 'Unggah tugas akhirmu dan pantau status review petugas.'" />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-stat-card label="Menunggu Review" :value="$ringkasan['Menunggu']" icon="bi-clock-history" tone="yellow" />
        <x-stat-card label="Disetujui" :value="$ringkasan['Disetujui']" icon="bi-check-circle" tone="green" />
        <x-stat-card label="Ditolak" :value="$ringkasan['Ditolak']" icon="bi-x-circle" tone="red" />
    </div>

    @if ($staff)
        <form method="POST" action="{{ route('petugas.tugas-akhir.store') }}" enctype="multipart/form-data" class="card mb-6 grid grid-cols-1 gap-3 p-5 md:grid-cols-2">
            @csrf
            <div class="md:col-span-2">
                <h2 class="font-bold">Masukkan tugas akhir</h2>
                <p class="text-xs text-stone-500">Tugas akhir hanya dimasukkan oleh petugas/admin atas nama anggota. Langsung berstatus Disetujui dan tampil di Koleksi Tugas Akhir.</p>
            </div>
            @if ($errors->any())
                <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 md:col-span-2">
                    @foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif
            <div>
                <label class="label">NIM / NIP penulis</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required maxlength="50" placeholder="Contoh: 2025573010034" class="input">
            </div>
            <div>
                <label class="label">Pembimbing (opsional)</label>
                <input type="text" name="pembimbing" value="{{ old('pembimbing') }}" maxlength="150" class="input">
            </div>
            <div class="md:col-span-2">
                <label class="label">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="250" class="input">
            </div>
            <div class="md:col-span-2">
                <label class="label">Berkas PDF (maks. 10 MB)</label>
                <input type="file" name="file" accept="application/pdf" required class="input !py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-yellow-800">
            </div>
            <div class="md:col-span-2"><button class="btn btn-primary"><i class="bi bi-upload"></i> Simpan Tugas Akhir</button></div>
        </form>
    @endif

    @if (auth()->user()->role === 'mahasiswa')
        <form method="POST" action="{{ route('anggota.tugas-akhir.store') }}" enctype="multipart/form-data" class="card mb-6 grid grid-cols-1 gap-3 p-5 md:grid-cols-2">
            @csrf
            <div class="md:col-span-2">
                <h2 class="font-bold">Unggah tugas akhir</h2>
                <p class="text-xs text-stone-500">Berkas akan direview petugas. Setelah disetujui, tampil di Koleksi Tugas Akhir.</p>
            </div>
            @if ($errors->any())
                <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 md:col-span-2">
                    @foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif
            <div class="md:col-span-2">
                <label class="label">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="250" class="input">
            </div>
            <div>
                <label class="label">Pembimbing (opsional)</label>
                <input type="text" name="pembimbing" value="{{ old('pembimbing') }}" maxlength="150" class="input">
            </div>
            <div>
                <label class="label">Berkas PDF (maks. 10 MB)</label>
                <input type="file" name="file" accept="application/pdf" required class="input !py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-yellow-800">
            </div>
            <div class="md:col-span-2"><button class="btn btn-primary"><i class="bi bi-upload"></i> Kirim Tugas Akhir</button></div>
        </form>
    @endif

    @if ($staff)
        <form method="GET" class="mb-4 flex gap-2">
            @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau judul..." class="input max-w-sm">
            <button class="btn btn-primary">Cari</button>
        </form>
    @endif

    <x-status-tabs :options="['Menunggu' => 'Menunggu', 'Disetujui' => 'Disetujui', 'Ditolak' => 'Ditolak']" />

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>@if ($staff)<th>Pengunggah</th>@endif<th>Judul</th><th>Status</th><th>Catatan</th>@if ($staff)<th class="text-right">Review</th>@endif</tr>
            </thead>
            <tbody>
                @forelse ($tugasAkhir as $ta)
                    <tr>
                        @if ($staff)<td><x-user-cell :user="$ta->user" /></td>@endif
                        <td class="max-w-sm">
                            <p class="font-semibold text-stone-800"><a href="{{ route('tugas-akhir.show', $ta->id) }}" class="hover:text-yellow-700 hover:underline">{{ $ta->judul }}</a></p>
                            @if ($ta->pembimbing)<p class="text-xs text-stone-400">Pembimbing: {{ $ta->pembimbing }}</p>@endif
                            @if ($staff && $adaBerkas($ta->file_tugas))
                                <a href="{{ asset('storage/'.$ta->file_tugas) }}" target="_blank" class="text-xs font-semibold text-yellow-700 hover:underline"><i class="bi bi-file-earmark-pdf"></i> Buka PDF</a>
                            @elseif ($staff)
                                <span class="text-xs text-red-500"><i class="bi bi-exclamation-circle"></i> PDF belum diunggah</span>
                            @endif
                            @if ($staff)
                                <form method="POST" action="{{ route('petugas.tugas-akhir.berkas.update', $ta) }}" enctype="multipart/form-data" class="mt-1 flex items-center gap-1">
                                    @csrf
                                    <input type="file" name="file" accept="application/pdf" required class="max-w-[11rem] text-xs file:mr-2 file:rounded file:border-0 file:bg-stone-100 file:px-2 file:py-0.5 file:text-xs">
                                    <button class="btn btn-sm">{{ $ta->file_tugas ? 'Ganti' : 'Unggah' }}</button>
                                </form>
                            @endif
                        </td>
                        <td><x-status-badge :status="$ta->status" /></td>
                        <td class="max-w-xs text-stone-500">
                            {{ $ta->catatan_reviewer ?: '-' }}
                            @if ($ta->reviewer)<p class="text-xs text-stone-400">oleh {{ $ta->reviewer->nama }}</p>@endif
                        </td>
                        @if ($staff)
                            <td>
                                @if ($ta->status === 'Menunggu')
                                    <form method="POST" action="{{ route('petugas.tugas-akhir.review', $ta) }}" class="flex flex-col items-end gap-2">
                                        @csrf
                                        <input type="text" name="catatan_reviewer" placeholder="Catatan (opsional)" maxlength="1000" class="input !py-1.5 text-xs">
                                        <div class="flex gap-2">
                                            <button name="keputusan" value="Disetujui" class="btn btn-sm btn-success">Setujui</button>
                                            <button name="keputusan" value="Ditolak" class="btn btn-sm btn-danger">Tolak</button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-right text-stone-300">-</p>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <x-empty-state :cols="$staff ? 5 : 3" icon="bi-mortarboard" title="Belum ada tugas akhir" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tugasAkhir->links() }}</div>
@endsection
