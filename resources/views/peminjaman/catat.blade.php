@extends('layouts.app')
@section('title', 'Catat Peminjaman')
@section('page-title', 'Catat Peminjaman')

@section('content')

    <form method="POST" action="{{ route('petugas.peminjaman.simpan') }}" class="bg-white border rounded-xl p-6 max-w-xl space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">NIM / NIP Anggota</label>
            <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required autofocus autocomplete="off"
                   placeholder="Scan barcode kartu atau ketik NIM/NIP"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Buku yang Dipinjam</label>
            <div id="barisBuku" class="space-y-2">
                @php $lama = old('buku_id', ['']); @endphp
                @foreach ($lama as $terpilih)
                    <div class="flex gap-2 baris-buku">
                        <select name="buku_id[]" required class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                            <option value="">-- Pilih buku yang tersedia --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->id }}" @selected($terpilih == $b->id)>{{ $b->judul }} (stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                        <button type="button" class="hapus-baris text-xs text-red-500 hover:text-red-600 px-2">Hapus</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="tambahBuku" class="mt-2 text-xs text-gold-700 hover:underline">+ Tambah buku lain (maks. 5)</button>
            <p class="text-xs text-gray-400 mt-2">Peminjaman langsung berstatus Dipinjam. Stok berkurang dan jatuh tempo dihitung otomatis sesuai jenis anggota.</p>
        </div>

        <button class="bg-gold-600 hover:bg-gold-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Catat Peminjaman
        </button>
    </form>

@endsection

@push('scripts')
<script>
    (function () {
        var wadah = document.getElementById('barisBuku');

        document.getElementById('tambahBuku').addEventListener('click', function () {
            var baris = wadah.querySelectorAll('.baris-buku');
            if (baris.length >= 5) return;
            var salin = baris[0].cloneNode(true);
            salin.querySelector('select').value = '';
            wadah.appendChild(salin);
        });

        wadah.addEventListener('click', function (e) {
            if (!e.target.classList.contains('hapus-baris')) return;
            if (wadah.querySelectorAll('.baris-buku').length > 1) e.target.closest('.baris-buku').remove();
        });
    })();
</script>
@endpush
