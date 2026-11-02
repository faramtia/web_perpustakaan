@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $event->judul)

@section('content')
@php
    $user = auth()->user();
    $penuh = $event->kuota > 0 && $event->peserta_count >= $event->kuota;
    $berlalu = ($event->tanggal_selesai ?? $event->tanggal_mulai)?->lt(today());
@endphp

    <a href="{{ route('event.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm font-semibold text-yellow-700 hover:underline"><i class="bi bi-arrow-left"></i> Semua event</a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <span class="chip bg-yellow-100 text-yellow-800"><i class="bi bi-calendar3 mr-1"></i>
                {{ $event->tanggal_mulai?->translatedFormat('l, d F Y') }}@if ($event->tanggal_selesai) – {{ $event->tanggal_selesai->translatedFormat('d F Y') }}@endif
            </span>
            <h1 class="mt-3 text-2xl font-extrabold text-stone-900">{{ $event->judul }}</h1>
            <p class="mt-1 text-sm text-stone-500"><i class="bi bi-geo-alt"></i> {{ $event->lokasi ?: 'Perpustakaan PNL' }}</p>
            <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-stone-700">{{ $event->deskripsi ?: 'Belum ada deskripsi untuk event ini.' }}</div>
        </div>

        <div class="space-y-4">
            <div class="card p-5">
                <p class="text-xs font-semibold uppercase text-stone-400">Peserta</p>
                <p class="text-2xl font-extrabold">{{ $event->peserta_count }}<span class="text-base font-semibold text-stone-400">{{ $event->kuota > 0 ? ' / '.$event->kuota : ' (tanpa batas)' }}</span></p>

                <div class="mt-4">
                    @if ($user?->isAnggota())
                        @if ($sudahDaftar)
                            <x-status-badge status="Terdaftar" />
                        @elseif ($berlalu)
                            <p class="text-sm text-stone-400">Event sudah berlalu.</p>
                        @elseif ($penuh)
                            <p class="text-sm font-semibold text-red-500">Kuota penuh.</p>
                        @else
                            <form method="POST" action="{{ route('anggota.event.daftar', $event) }}">
                                @csrf <button class="btn btn-primary w-full">Daftar Event</button>
                            </form>
                        @endif
                    @elseif (! $user)
                        <a href="{{ route('login') }}" class="btn btn-primary w-full">Masuk untuk mendaftar</a>
                    @endif
                </div>
            </div>

            @if ($user?->isAdmin())
                <form method="POST" action="{{ route('admin.event.destroy', $event) }}" onsubmit="return confirm('Hapus event ini beserta data pesertanya?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger w-full"><i class="bi bi-trash"></i> Hapus Event</button>
                </form>
            @endif
        </div>
    </div>

    @if ($user?->isStaff())
        <div class="table-wrap mt-6">
            <table class="tbl">
                <thead><tr><th>Peserta</th><th>Tanggal daftar</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($peserta as $p)
                        <tr>
                            <td><x-user-cell :user="$p->user" /></td>
                            <td>{{ $p->tanggal_daftar?->format('d M Y') ?? '-' }}</td>
                            <td><x-status-badge :status="$p->status_pendaftaran" /></td>
                        </tr>
                    @empty
                        <x-empty-state cols="3" icon="bi-people" title="Belum ada peserta" />
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
