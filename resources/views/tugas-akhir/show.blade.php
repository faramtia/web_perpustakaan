@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $ta->judul)

@section('content')
    <a href="{{ route('tugas-akhir.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm font-semibold text-yellow-700 hover:underline">
        <i class="bi bi-arrow-left"></i> Kembali ke Koleksi Tugas Akhir
    </a>

    <div class="card mb-5 p-6">
        <h1 class="mb-3 text-xl font-bold leading-snug text-stone-800">{{ $ta->judul }}</h1>
        <dl class="grid grid-cols-1 gap-x-8 gap-y-2 text-sm sm:grid-cols-2">
            <div><dt class="text-xs text-stone-400">Penulis</dt><dd class="font-medium text-stone-700">{{ $ta->user->nama ?? '-' }}</dd></div>
            <div><dt class="text-xs text-stone-400">NIM/NIP</dt><dd class="font-medium text-stone-700">{{ $ta->user->nim_nip ?? '-' }}</dd></div>
            <div><dt class="text-xs text-stone-400">Pembimbing</dt><dd class="font-medium text-stone-700">{{ $ta->pembimbing ?: '-' }}</dd></div>
            <div><dt class="text-xs text-stone-400">Status</dt><dd><x-status-badge :status="$ta->status" /></dd></div>
        </dl>

        <div class="mt-5 flex flex-wrap gap-2 border-t border-stone-100 pt-4">
            @auth
                @if ($adaBerkas)
                    <a href="{{ route('tugas-akhir.unduh', $ta->id) }}" class="btn btn-primary"><i class="bi bi-download"></i> Unduh PDF</a>
                    <a href="{{ route('tugas-akhir.berkas', $ta->id) }}" target="_blank" class="btn"><i class="bi bi-box-arrow-up-right"></i> Buka di tab baru</a>
                @else
                    <span class="text-sm text-stone-400">Berkas PDF belum tersedia.</span>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary"><i class="bi bi-lock"></i> Login untuk membaca &amp; mengunduh PDF</a>
            @endauth
        </div>
    </div>

    @auth
        @if ($adaBerkas)
            <div class="card overflow-hidden p-0">
                <iframe src="{{ route('tugas-akhir.berkas', $ta->id) }}" title="PDF {{ $ta->judul }}" class="h-[80vh] w-full border-0"></iframe>
            </div>
        @endif
    @endauth
@endsection
