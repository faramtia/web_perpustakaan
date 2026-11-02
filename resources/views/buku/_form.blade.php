@php $b = $buku ?? null; @endphp

<div>
    <label class="label">Judul</label>
    <input type="text" name="judul" value="{{ old('judul', $b->judul ?? '') }}" required maxlength="200" class="input">
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="label">Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis', $b->penulis ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">Penerbit</label>
        <input type="text" name="penerbit" value="{{ old('penerbit', $b->penerbit ?? '') }}" class="input">
    </div>
    <div>
        <label class="label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $b->tahun_terbit ?? '') }}" min="1901" max="2155" class="input">
    </div>
    <div>
        <label class="label">ISBN</label>
        <input type="text" name="isbn" value="{{ old('isbn', $b->isbn ?? '') }}" maxlength="50" class="input">
    </div>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <label class="label">Kategori</label>
        <select name="kategori_id" required class="input">
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $b->kategori_id ?? null) == $k->id)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Lokasi Rak</label>
        <select name="lokasi_id" required class="input">
            @foreach ($lokasi as $l)
                <option value="{{ $l->id }}" @selected(old('lokasi_id', $b->lokasi_id ?? null) == $l->id)>{{ $l->nama_ruang }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Tipe Koleksi</label>
        <select name="tipe_koleksi_id" required class="input">
            @foreach ($tipeKoleksi as $t)
                <option value="{{ $t->id }}" @selected(old('tipe_koleksi_id', $b->tipe_koleksi_id ?? null) == $t->id)>{{ $t->nama_tipe }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="label">Stok (eksemplar)</label>
    <input type="number" name="stok" value="{{ old('stok', $b->stok ?? 0) }}" min="0" required class="input sm:w-40">
</div>
