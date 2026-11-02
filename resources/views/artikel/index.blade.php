@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Artikel & Blog')

@section('content')
@php $admin = auth()->user()?->isAdmin(); @endphp

    <x-page-header icon="bi-newspaper" title="Artikel & Blog" subtitle="Berita, tips, panduan, dan tulisan informatif dari perpustakaan.">
        @if ($admin)
            <a href="{{ route('admin.artikel.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg text-yellow-400"></i> Tulis Artikel</a>
        @endif
    </x-page-header>

    <form method="GET" class="mb-5 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." class="input max-w-xs">
        <select name="kategori" class="input max-w-[12rem]">
            <option value="">Semua kategori</option>
            @foreach ($kategori as $k)<option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
        </select>
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($artikel as $a)
            <a href="{{ route('artikel.show', $a) }}" class="card group flex flex-col overflow-hidden transition hover:shadow-md">
                <div class="relative grid aspect-video place-items-center bg-gradient-to-br from-yellow-50 to-amber-100 text-5xl text-yellow-600/70">
                    <i class="bi bi-journal-text"></i>
                    <span class="absolute left-3 top-3 rounded-lg bg-white/90 px-3 py-1 text-[11px] font-bold text-yellow-800 shadow-sm">{{ $a->kategori->nama_kategori ?? 'Artikel' }}</span>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <p class="mb-2 text-xs text-stone-400">
                        <i class="bi bi-calendar-event text-yellow-600"></i> {{ optional($a->tanggal_upload)->translatedFormat('d M Y') }}
                        &middot; <i class="bi bi-person text-yellow-600"></i> {{ $a->penulis->nama ?? '-' }}
                    </p>
                    <h3 class="mb-2 line-clamp-2 font-extrabold text-stone-900 group-hover:text-yellow-700">{{ $a->judul }}</h3>
                    <p class="line-clamp-3 text-xs leading-relaxed text-stone-500">{{ $a->isi_artikel }}</p>
                    <span class="mt-auto pt-4 text-xs font-bold text-stone-900">Baca selengkapnya <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
        @empty
            <div class="col-span-full"><x-empty-state icon="bi-newspaper" title="Belum ada artikel dipublikasikan" /></div>
        @endforelse
    </div>

    <div class="mt-6">{{ $artikel->links() }}</div>
@endsection
