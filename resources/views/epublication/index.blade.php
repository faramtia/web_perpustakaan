@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'E-Publication')
@section('page-title', 'E-Publication')

@section('content')

    @php
        $isTa = $jenis === 'tugas-akhir';
    @endphp

    {{-- Tab jenis publikasi --}}
    <div class="flex gap-1 border-b mb-5 text-sm font-medium">
        <a href="{{ route('epublication.index', ['jenis' => 'jurnal']) }}"
           class="px-4 py-2.5 border-b-2 -mb-px {{ ! $isTa ? 'border-gold-500 text-gold-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            📰 E-Jurnal
        </a>
        <a href="{{ route('epublication.index', ['jenis' => 'tugas-akhir']) }}"
           class="px-4 py-2.5 border-b-2 -mb-px {{ $isTa ? 'border-gold-500 text-gold-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            🎓 Tugas Akhir
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="jenis" value="{{ $jenis }}">
            @unless ($isTa)
                <select name="kategori_id" class="border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-400">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            @endunless
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="{{ $isTa ? 'Cari judul, penulis, pembimbing...' : 'Cari judul/penulis jurnal...' }}"
                   class="border rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-gold-400">
            <button class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg">Cari</button>
        </form>

        @if ($bisaKelola)
            <a href="{{ route('admin.epublication.create', ['jenis' => $jenis]) }}"
               class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-medium px-4 py-2 rounded-lg text-center">
                + Tambah {{ $isTa ? 'Tugas Akhir' : 'E-Jurnal' }}
            </a>
        @endif
    </div>

    @if (! $isTa)
        {{-- ===== E-JURNAL ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($items as $j)
                <div class="bg-white border rounded-xl p-5">
                    <p class="text-xs text-gold-700 font-medium uppercase mb-1">{{ $j->kategori->nama_kategori ?? 'Umum' }}</p>
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $j->judul }}</h3>
                    <p class="text-xs text-gray-400 mb-2">{{ $j->penulis }} &middot; {{ $j->tahun_terbit }}</p>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $j->abstrak }}</p>
                    <div class="mt-3 flex items-center justify-between gap-2">
                        @auth
                            <a href="{{ route('epublication.jurnal.baca', $j) }}" target="_blank" rel="noopener"
                               class="text-xs bg-gold-600 hover:bg-gold-700 text-white px-3 py-1.5 rounded-lg">📖 Baca Jurnal</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs bg-gray-100 text-gray-500 hover:bg-gray-200 px-3 py-1.5 rounded-lg">Masuk untuk membaca</a>
                        @endauth
                        <span class="text-xs text-gray-400">📍 Atau di ruang jurnal</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 italic col-span-full text-center py-10">Belum ada e-jurnal.</p>
            @endforelse
        </div>
    @else
        {{-- ===== TUGAS AKHIR ===== --}}
        <div class="bg-white rounded-xl border overflow-hidden overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium">Judul</th>
                        <th class="px-5 py-3 font-medium">Penulis</th>
                        <th class="px-5 py-3 font-medium">Pembimbing</th>
                        @if ($bisaKelola)
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($items as $ta)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $ta->judul }}</td>
                            <td class="px-5 py-3">{{ $ta->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $ta->pembimbing ?: '-' }}</td>
                            @if ($bisaKelola)
                                <td class="px-5 py-3"><x-status-badge :status="$ta->status" /></td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($ta->file_tugas)
                                            <a href="{{ asset('storage/'.$ta->file_tugas) }}" target="_blank" rel="noopener"
                                               class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg">Buka File</a>
                                        @endif
                                        @if ($ta->status === 'Menunggu')
                                            <form method="POST" action="{{ route('admin.epublication.tugas-akhir.review', $ta) }}">
                                                @csrf
                                                <input type="hidden" name="keputusan" value="Disetujui">
                                                <button class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg">Setujui</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.epublication.tugas-akhir.review', $ta) }}">
                                                @csrf
                                                <input type="hidden" name="keputusan" value="Ditolak">
                                                <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg">Tolak</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $bisaKelola ? 5 : 3 }}" class="px-5 py-6 text-center text-gray-400 italic">Belum ada tugas akhir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="text-xs text-gray-400 mt-3">Versi lengkap tugas akhir dapat dibaca di perpustakaan.</p>
    @endif

    <div class="mt-4">{{ $items->links() }}</div>

@endsection
