@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Tugas Akhir')

@section('content')
    <x-page-header icon="bi-mortarboard" title="Koleksi Tugas Akhir" subtitle="Tugas akhir yang sudah direview dan disetujui petugas perpustakaan. Berkas PDF dapat dibaca setelah login." />

    <form method="GET" class="mb-5 flex flex-wrap gap-2">
        <div class="relative w-full max-w-xs">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul, penulis, pembimbing..." class="input pl-10">
        </div>
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($koleksi as $ta)
            <article class="card flex flex-col p-5 transition hover:ring-yellow-400 hover:shadow-md">
                <h3 class="mb-1 font-bold leading-snug text-stone-800">{{ $ta->judul }}</h3>
                <p class="text-xs text-stone-400"><i class="bi bi-person"></i> {{ $ta->user->nama ?? '-' }}</p>
                @if ($ta->pembimbing)
                    <p class="mb-3 text-xs text-stone-400"><i class="bi bi-person-badge"></i> Pembimbing: {{ $ta->pembimbing }}</p>
                @endif

                <div class="mt-auto border-t border-stone-100 pt-3 text-sm" style="margin-top:1rem">
                    @auth
                        @if ($adaBerkas($ta->file_tugas))
                            <a href="{{ asset('storage/'.$ta->file_tugas) }}" target="_blank" class="font-semibold text-yellow-700 hover:underline">
                                <i class="bi bi-file-earmark-pdf"></i> Baca PDF
                            </a>
                        @else
                            <span class="text-xs text-stone-400">Berkas belum tersedia.</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-yellow-700 hover:underline">
                            <i class="bi bi-lock"></i> Login untuk membaca PDF
                        </a>
                    @endauth
                </div>
            </article>
        @empty
            <div class="col-span-full"><x-empty-state icon="bi-mortarboard" title="Belum ada tugas akhir" /></div>
        @endforelse
    </div>

    <div class="mt-6">{{ $koleksi->links() }}</div>
@endsection
