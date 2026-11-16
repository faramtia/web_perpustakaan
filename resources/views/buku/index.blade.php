@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Katalog Buku')

@section('content')
@php
    $user = auth()->user();
    $staff = $user?->isStaff();
    $anggota = $user?->isAnggota();
@endphp

    <x-page-header icon="bi-book" :title="$staff ? 'Data Buku' : 'Katalog Buku'"
        :subtitle="$staff ? 'Kelola koleksi buku perpustakaan.' : 'Cari koleksi buku perpustakaan.'">
        @if ($staff)
            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
        @endif
    </x-page-header>

    <form method="GET" class="card mb-4 p-4">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="relative lg:col-span-2">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul, penulis, penerbit, ISBN..." class="input pl-10">
            </div>
            <select name="kategori" class="input">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <select name="tipe" class="input">
                <option value="">Semua tipe koleksi</option>
                @foreach ($tipeKoleksi as $t)
                    <option value="{{ $t->id }}" @selected(request('tipe') == $t->id)>{{ $t->nama_tipe }}</option>
                @endforeach
            </select>
            <select name="ketersediaan" class="input">
                <option value="">Semua stok</option>
                <option value="tersedia" @selected(request('ketersediaan') === 'tersedia')>Tersedia</option>
                <option value="habis" @selected(request('ketersediaan') === 'habis')>Habis</option>
            </select>
        </div>

        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <select name="penulis" class="input">
                <option value="">Semua penulis</option>
                @foreach ($daftarPenulis as $nama)
                    <option value="{{ $nama }}" @selected(request('penulis') === $nama)>{{ $nama }}</option>
                @endforeach
            </select>
            <select name="penerbit" class="input">
                <option value="">Semua penerbit</option>
                @foreach ($daftarPenerbit as $nama)
                    <option value="{{ $nama }}" @selected(request('penerbit') === $nama)>{{ $nama }}</option>
                @endforeach
            </select>
            <select name="tahun" class="input">
                <option value="">Semua tahun terbit</option>
                @foreach ($daftarTahun as $th)
                    <option value="{{ $th }}" @selected((string) request('tahun') === (string) $th)>{{ $th }}</option>
                @endforeach
            </select>
            <div class="flex gap-2 lg:col-span-2">
                <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                @if (request()->hasAny(['q', 'kategori', 'tipe', 'ketersediaan', 'penulis', 'penerbit', 'tahun']))
                    <a href="{{ url()->current() }}" class="btn btn-soft">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Judul</th><th>Kategori</th><th>Tipe</th><th>Lokasi</th><th>Stok</th>
                    @if ($staff || $anggota)<th class="text-right">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($buku as $item)
                    <tr>
                        <td class="max-w-sm">
                            <p class="font-semibold text-stone-800">{{ $item->judul }}</p>
                            <p class="text-xs text-stone-400">{{ $item->penulis }}@if ($item->tahun_terbit) &middot; {{ $item->tahun_terbit }}@endif @if ($item->penerbit) &middot; {{ $item->penerbit }}@endif</p>
                        </td>
                        <td><span class="chip bg-yellow-100 text-yellow-700">{{ $item->kategori->nama_kategori ?? '-' }}</span></td>
                        <td><span class="chip bg-blue-100 text-blue-700">{{ $item->tipeKoleksi->nama_tipe ?? '-' }}</span></td>
                        <td class="text-stone-500">{{ $item->lokasi->nama_ruang ?? '-' }}</td>
                        <td>
                            <span class="chip min-w-8 text-center {{ $item->stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">{{ (int) $item->stok }}</span>
                        </td>
                        @if ($staff)
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.buku.edit', $item) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Edit</a>
                                    <form method="POST" action="{{ route('admin.buku.destroy', $item) }}" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        @elseif ($anggota)
                            <td class="text-right">
                                @if ($item->tersedia())
                                    <a href="{{ route('anggota.peminjaman.create', ['buku' => $item->id]) }}" class="btn btn-sm btn-success">Pinjam</a>
                                @else
                                    <form method="POST" action="{{ route('anggota.reservasi.store') }}">
                                        @csrf <input type="hidden" name="buku_id" value="{{ $item->id }}">
                                        <button class="btn btn-sm bg-yellow-100 text-yellow-800 hover:bg-yellow-200">Reservasi</button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <x-empty-state :cols="$staff || $anggota ? 6 : 5" icon="bi-book" title="Buku tidak ditemukan" text="Coba ubah kata kunci atau filter." />
                @endforelse
            </tbody>
        </table>
    </div>

    @guest
        <p class="mt-4 text-sm text-stone-500"><a href="{{ route('login') }}" class="font-semibold text-yellow-700 hover:underline">Masuk</a> untuk meminjam atau mereservasi buku.</p>
    @endguest

    <div class="mt-4">{{ $buku->links() }}</div>
@endsection
