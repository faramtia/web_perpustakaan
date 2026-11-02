@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $artikel->judul)

@section('content')
    <a href="{{ route('artikel.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm font-semibold text-yellow-700 hover:underline"><i class="bi bi-arrow-left"></i> Semua artikel</a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <article class="card p-6 sm:p-8 lg:col-span-2">
            <span class="chip bg-yellow-100 text-yellow-800">{{ $artikel->kategori->nama_kategori ?? 'Artikel' }}</span>
            <h1 class="mt-3 text-2xl font-extrabold text-stone-900 sm:text-3xl">{{ $artikel->judul }}</h1>
            <p class="mt-2 text-xs text-stone-400">
                <i class="bi bi-person"></i> {{ $artikel->penulis->nama ?? '-' }} &middot;
                <i class="bi bi-calendar-event"></i> {{ optional($artikel->tanggal_upload)->translatedFormat('d F Y') }}
            </p>
            <div class="mt-5 whitespace-pre-line text-sm leading-relaxed text-stone-700">{{ $artikel->isi_artikel }}</div>

            @if (auth()->user()?->isAdmin())
                <form method="POST" action="{{ route('admin.artikel.destroy', $artikel) }}" onsubmit="return confirm('Hapus artikel ini?')" class="mt-6 border-t border-stone-100 pt-4">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus Artikel</button>
                </form>
            @endif
        </article>

        <aside class="card h-fit p-5">
            <h2 class="mb-3 font-bold text-stone-800">Artikel lainnya</h2>
            <ul class="divide-y divide-stone-100 text-sm">
                @forelse ($lainnya as $a)
                    <li class="py-2.5"><a href="{{ route('artikel.show', $a) }}" class="font-semibold text-stone-700 hover:text-yellow-700">{{ $a->judul }}</a>
                        <p class="text-xs text-stone-400">{{ $a->kategori->nama_kategori ?? '' }} &middot; {{ optional($a->tanggal_upload)->format('d M Y') }}</p></li>
                @empty
                    <li class="italic text-stone-400">Belum ada artikel lain.</li>
                @endforelse
            </ul>
        </aside>
    </div>
@endsection
