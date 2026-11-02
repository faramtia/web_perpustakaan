@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')

@section('content')
    <x-page-header icon="bi-journal-arrow-up" title="Ajukan Peminjaman" subtitle="Pilih buku yang tersedia. Petugas akan memverifikasi pengajuanmu." />

    <form method="POST" action="{{ route('anggota.peminjaman.store') }}" class="card max-w-3xl p-6">
        @csrf

        <p class="label">Pilih buku (maksimal {{ $maksBuku }})</p>

        @if ($buku->isEmpty())
            <p class="rounded-xl bg-stone-50 p-4 text-sm italic text-stone-500">Semua buku sedang dipinjam. Buat reservasi dari menu Reservasi Saya.</p>
        @else
            <input type="text" id="cariBuku" placeholder="Saring daftar buku..." class="input mb-3">
            <div id="daftarBuku" class="max-h-96 space-y-2 overflow-y-auto rounded-xl border border-stone-200 p-2">
                @foreach ($buku as $b)
                    <label data-judul="{{ strtolower($b->judul.' '.$b->penulis) }}"
                           class="flex cursor-pointer items-center gap-3 rounded-lg p-3 hover:bg-yellow-50 has-[:checked]:bg-yellow-50 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">
                        <input type="checkbox" name="buku_id[]" value="{{ $b->id }}" class="rounded border-stone-300 text-yellow-500 focus:ring-yellow-300"
                               @checked(in_array($b->id, (array) old('buku_id', $dipilih ? [$dipilih] : [])))>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold text-stone-800">{{ $b->judul }}</span>
                            <span class="block truncate text-xs text-stone-400">{{ $b->penulis }} &middot; {{ $b->lokasi->nama_ruang ?? '-' }}</span>
                        </span>
                        <span class="chip bg-emerald-100 text-emerald-700">stok {{ $b->stok }}</span>
                    </label>
                @endforeach
            </div>
        @endif

        @if ($aturan)
            <p class="hint mt-3">Masa pinjam {{ $aturan->lama_pinjam_hari }} hari sejak disetujui. Denda keterlambatan Rp{{ number_format($aturan->denda_per_hari, 0, ',', '.') }} per hari.</p>
        @endif

        <div class="mt-5 flex gap-3">
            <button class="btn btn-primary" @disabled($buku->isEmpty())><i class="bi bi-send"></i> Ajukan Peminjaman</button>
            <a href="{{ route('anggota.peminjaman.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const cari = document.getElementById('cariBuku');
    if (cari) {
        cari.addEventListener('input', () => {
            const k = cari.value.toLowerCase();
            document.querySelectorAll('#daftarBuku label').forEach(l => l.classList.toggle('hidden', !l.dataset.judul.includes(k)));
        });
        document.getElementById('daftarBuku').addEventListener('change', () => {
            const dipilih = document.querySelectorAll('#daftarBuku input:checked');
            if (dipilih.length >= {{ $maksBuku }}) {
                document.querySelectorAll('#daftarBuku input:not(:checked)').forEach(i => i.disabled = true);
            } else {
                document.querySelectorAll('#daftarBuku input').forEach(i => i.disabled = false);
            }
        });
    }
</script>
@endpush
