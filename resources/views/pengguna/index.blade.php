@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
    <x-page-header icon="bi-people" title="Pengguna" subtitle="Kelola akun admin, petugas, dosen, dan mahasiswa.">
        <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Tambah Pengguna</a>
    </x-page-header>

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama, email, NIM / NIP..." class="input max-w-xs">
        <select name="role" class="input max-w-[11rem]">
            <option value="">Semua role</option>
            @foreach ($roles as $r)<option value="{{ $r->id }}" @selected(request('role') == $r->id)>{{ $r->nama_role }}</option>@endforeach
        </select>
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="table-wrap">
        <table class="tbl">
            <thead><tr><th>Pengguna</th><th>NIM / NIP</th><th>Email</th><th>Role</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse ($pengguna as $u)
                    <tr>
                        <td><x-user-cell :user="$u" sub="" /></td>
                        <td class="text-stone-500">{{ $u->nim_nip }}</td>
                        <td class="text-stone-500">{{ $u->email }}</td>
                        <td><span class="chip bg-stone-100 text-stone-700">{{ $u->jenisUser->nama_role ?? '-' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.pengguna.edit', $u) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Edit</a>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state cols="5" icon="bi-people" title="Pengguna tidak ditemukan" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pengguna->links() }}</div>
@endsection
