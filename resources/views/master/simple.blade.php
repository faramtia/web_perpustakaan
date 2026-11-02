@extends('layouts.app')
@section('title', $c['judul'])

@section('content')
    <x-page-header :icon="$c['ikon']" :title="$c['judul']" :subtitle="$c['deskripsi']" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route($c['route'].'.store') }}" class="card h-fit space-y-3 p-5">
            @csrf
            <h2 class="font-bold text-stone-800">Tambah {{ $c['label'] }}</h2>
            <input type="text" name="{{ $c['kolom'] }}" value="{{ old($c['kolom']) }}" required maxlength="100" placeholder="{{ $c['placeholder'] }}" class="input">
            <button class="btn btn-primary w-full"><i class="bi bi-plus-lg"></i> Tambah</button>
        </form>

        <div class="table-wrap lg:col-span-2">
            <table class="tbl">
                <thead>
                    <tr><th>{{ $c['label'] }}</th><th>Dipakai</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        @php
                            $pakai = collect($c['relasi'])->map(fn ($nama, $rel) => $item->{$rel.'_count'}.' '.$nama)->implode(', ');
                            $formId = 'edit-'.$item->id;
                        @endphp
                        <tr>
                            <td>
                                <form id="{{ $formId }}" method="POST" action="{{ route($c['route'].'.update', $item) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="{{ $c['kolom'] }}" value="{{ $item->{$c['kolom']} }}" required maxlength="100"
                                           class="input !border-transparent !bg-transparent !px-0 font-semibold hover:!border-stone-200 focus:!bg-white focus:!px-4">
                                </form>
                            </td>
                            <td class="text-stone-500">{{ $pakai }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <button form="{{ $formId }}" class="btn btn-sm btn-soft"><i class="bi bi-check-lg"></i> Simpan</button>
                                    <form method="POST" action="{{ route($c['route'].'.destroy', $item) }}" onsubmit="return confirm('Hapus {{ strtolower($c['label']) }} ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state cols="3" :icon="$c['ikon']" title="Belum ada data" />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
