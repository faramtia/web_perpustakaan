@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Event & Agenda')

@section('content')
@php $admin = auth()->user()?->isAdmin(); $lalu = request('waktu') === 'lalu'; @endphp

    <x-page-header icon="bi-calendar-event" title="Event Semester" subtitle="Seminar, workshop, dan pelatihan literasi di perpustakaan.">
        <a href="{{ route('event.index') }}" class="btn btn-sm {{ $lalu ? 'btn-soft' : 'btn-dark' }}">Mendatang</a>
        <a href="{{ route('event.index', ['waktu' => 'lalu']) }}" class="btn btn-sm {{ $lalu ? 'btn-dark' : 'btn-soft' }}">Sudah berlalu</a>
    </x-page-header>

    @if ($admin)
        <details class="card mb-6 p-5" @if ($errors->any()) open @endif>
            <summary class="cursor-pointer font-bold text-stone-800"><i class="bi bi-plus-circle mr-1 text-yellow-600"></i> Tambah event baru</summary>
            <form method="POST" action="{{ route('admin.event.store') }}" class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2"><label class="label">Judul</label><input type="text" name="judul" value="{{ old('judul') }}" required maxlength="200" class="input"></div>
                <div><label class="label">Tanggal mulai</label><input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="input"></div>
                <div><label class="label">Tanggal selesai (opsional)</label><input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="input"></div>
                <div><label class="label">Lokasi</label><input type="text" name="lokasi" value="{{ old('lokasi') }}" required maxlength="100" class="input"></div>
                <div><label class="label">Kuota (kosong = tanpa batas)</label><input type="number" name="kuota" value="{{ old('kuota') }}" min="0" class="input"></div>
                <div class="md:col-span-2"><label class="label">Deskripsi</label><textarea name="deskripsi" rows="3" class="input">{{ old('deskripsi') }}</textarea></div>
                <div class="md:col-span-2"><button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Event</button></div>
            </form>
        </details>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($event as $e)
            @php $penuh = $e->kuota > 0 && $e->peserta_count >= $e->kuota; @endphp
            <div class="card flex flex-col justify-between p-5 transition hover:shadow-md">
                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <span class="chip bg-yellow-100 text-yellow-800"><i class="bi bi-calendar3 mr-1"></i>{{ $e->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}</span>
                        @if (in_array($e->id, $terdaftar))<x-status-badge status="Terdaftar" />@endif
                    </div>
                    <a href="{{ route('event.show', $e) }}" class="line-clamp-2 text-base font-extrabold text-stone-900 hover:text-yellow-700">{{ $e->judul }}</a>
                    <p class="mt-1 text-xs text-stone-400"><i class="bi bi-geo-alt"></i> {{ $e->lokasi ?: 'Perpustakaan PNL' }}</p>
                    @if ($e->deskripsi)<p class="mt-2 line-clamp-2 text-xs text-stone-500">{{ $e->deskripsi }}</p>@endif
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3">
                    <span class="text-xs font-bold text-stone-600"><i class="bi bi-people-fill mr-1 text-yellow-600"></i>
                        {{ $e->peserta_count }}{{ $e->kuota > 0 ? '/'.$e->kuota : '' }} peserta @if ($penuh)<span class="text-red-500">· penuh</span>@endif
                    </span>
                    <a href="{{ route('event.show', $e) }}" class="text-xs font-bold text-stone-900 hover:text-yellow-700">Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        @empty
            <div class="col-span-full"><x-empty-state icon="bi-calendar-x" :title="$lalu ? 'Belum ada event yang berlalu' : 'Belum ada event mendatang'" /></div>
        @endforelse
    </div>

    <div class="mt-6">{{ $event->links() }}</div>
@endsection
