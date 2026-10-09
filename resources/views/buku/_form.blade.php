@php $b = $buku ?? null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-600 mb-1">Judul</label>
    <input type="text" name="judul" value="{{ old('judul', $b->judul ?? '') }}" required
           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis', $b->penulis ?? '') }}"
               class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Penerbit</label>
        <input type="text" name="penerbit" value="{{ old('penerbit', $b->penerbit ?? '') }}"
               class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $b->tahun_terbit ?? '') }}"
               class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">ISBN</label>
        <input type="text" name="isbn" value="{{ old('isbn', $b->isbn ?? '') }}"
               class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
        <select name="kategori_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $b->kategori_id ?? null) == $k->id)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Lokasi Rak</label>
        <select name="lokasi_id" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
            <option value="">-</option>
            @foreach ($lokasi as $l)
                <option value="{{ $l->id }}" @selected(old('lokasi_id', $b->lokasi_id ?? null) == $l->id)>{{ $l->nama_ruang }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Tipe Koleksi</label>
        <select name="tipe_koleksi_id" required class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
            @foreach ($tipeKoleksi as $t)
                <option value="{{ $t->id }}" @selected(old('tipe_koleksi_id', $b->tipe_koleksi_id ?? null) == $t->id)>{{ $t->nama_tipe }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-600 mb-1">Stok</label>
    <input type="number" name="stok" value="{{ old('stok', $b->stok ?? 0) }}" min="0" required
           class="w-full sm:w-40 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
</div>
