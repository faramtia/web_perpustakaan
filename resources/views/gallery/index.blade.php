@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Gallery')

@section('content')
@php $kelola = auth()->user()?->isStaff(); @endphp

    <x-page-header icon="bi-images" title="Gallery" subtitle="Dokumentasi kegiatan dan suasana perpustakaan." />

    @if ($kelola)
        <form method="POST" action="{{ route('petugas.gallery.store') }}" enctype="multipart/form-data" class="card mb-6 flex flex-col gap-4 p-5 sm:flex-row sm:items-end">
            @csrf
            <div class="flex-1">
                <label class="label">Unggah foto (boleh lebih dari satu)</label>
                <input type="file" name="foto[]" accept="image/*" multiple required
                       class="input !py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-yellow-800">
                <p class="hint">jpg, png, gif, webp. Maksimal 2 MB per foto, 10 foto sekali unggah.</p>
            </div>
            <button class="btn btn-primary"><i class="bi bi-upload"></i> Unggah</button>
        </form>
    @endif

    @if ($foto->isEmpty())
        <x-empty-state icon="bi-image" title="Belum ada foto di gallery" />
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($foto as $f)
                <div class="card overflow-hidden">
                    <a href="{{ $f['url'] }}" target="_blank"><img src="{{ $f['url'] }}" alt="{{ $f['judul'] }}" class="h-48 w-full object-cover"></a>
                    <div class="flex items-start justify-between gap-3 p-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-stone-800">{{ $f['judul'] }}</p>
                            <p class="text-xs text-stone-400">{{ \Carbon\Carbon::createFromTimestamp($f['waktu'])->format('d M Y') }}</p>
                        </div>
                        @if ($kelola)
                            <form method="POST" action="{{ route('petugas.gallery.destroy') }}" onsubmit="return confirm('Hapus foto ini?')">
                                @csrf @method('DELETE')
                                <input type="hidden" name="nama" value="{{ $f['nama'] }}">
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
