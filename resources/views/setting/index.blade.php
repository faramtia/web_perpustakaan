@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
    <x-page-header icon="bi-gear" title="Pengaturan" subtitle="Aturan peminjaman per jenis pengguna (tabel jenis_user)." />

    <form method="POST" action="{{ route('admin.setting.update') }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="table-wrap">
            <table class="tbl">
                <thead><tr><th>Role</th><th>Jumlah pengguna</th><th>Lama pinjam (hari)</th><th>Denda per hari (Rp)</th></tr></thead>
                <tbody>
                    @foreach ($roles as $r)
                        <tr>
                            <td class="font-semibold">{{ $r->nama_role }}</td>
                            <td class="text-stone-500">{{ $r->users_count }}</td>
                            <td><input type="number" name="aturan[{{ $r->id }}][lama_pinjam_hari]" value="{{ old('aturan.'.$r->id.'.lama_pinjam_hari', $r->lama_pinjam_hari) }}" min="1" max="365" required class="input max-w-[8rem]"></td>
                            <td><input type="number" name="aturan[{{ $r->id }}][denda_per_hari]" value="{{ old('aturan.'.$r->id.'.denda_per_hari', $r->denda_per_hari) }}" min="0" step="100" required class="input max-w-[10rem]"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center gap-4">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Pengaturan</button>
            <p class="text-xs text-stone-400">Berlaku untuk peminjaman yang disetujui setelah ini. Denda yang sudah tercatat tidak berubah.</p>
        </div>
    </form>

    <div class="card mt-8 max-w-2xl p-5 text-sm text-stone-600">
        <h2 class="mb-2 font-bold text-stone-800">Informasi Perpustakaan</h2>
        <p>Perpustakaan Politeknik Negeri Lhokseumawe</p>
        <p>Jl. Banda Aceh–Medan Km. 280,3, Buketrata, Lhokseumawe, Aceh 24301</p>
        <p>Telp. (0645) 42670 · Senin–Jumat, 08.00–17.30 WIB</p>
    </div>
@endsection
