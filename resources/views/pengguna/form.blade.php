@extends('layouts.app')
@section('title', $pengguna ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
@php $u = $pengguna; @endphp
    <x-page-header :icon="$u ? 'bi-person-gear' : 'bi-person-plus'" :title="$u ? 'Edit Pengguna' : 'Tambah Pengguna'"
        subtitle="Akun petugas dan admin dibuat dari sini; mahasiswa & dosen juga bisa mendaftar sendiri." />

    <form method="POST" action="{{ $u ? route('admin.pengguna.update', $u) : route('admin.pengguna.store') }}" class="card max-w-2xl space-y-4 p-6">
        @csrf
        @if ($u) @method('PUT') @endif

        <div><label class="label">Nama lengkap</label><input type="text" name="nama" value="{{ old('nama', $u->nama ?? '') }}" required maxlength="100" class="input"></div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div><label class="label">NIM / NIP</label><input type="text" name="nim_nip" value="{{ old('nim_nip', $u->nim_nip ?? '') }}" required maxlength="50" class="input"></div>
            <div>
                <label class="label">Role</label>
                <select name="jenis_user_id" required class="input" @disabled($u && $u->id === auth()->id())>
                    @foreach ($roles as $r)<option value="{{ $r->id }}" @selected(old('jenis_user_id', $u->jenis_user_id ?? null) == $r->id)>{{ $r->nama_role }}</option>@endforeach
                </select>
                @if ($u && $u->id === auth()->id())
                    <input type="hidden" name="jenis_user_id" value="{{ $u->jenis_user_id }}">
                    <p class="hint">Role akunmu sendiri tidak bisa diubah.</p>
                @endif
            </div>
        </div>
        <div><label class="label">Email</label><input type="email" name="email" value="{{ old('email', $u->email ?? '') }}" required maxlength="100" class="input"></div>
        <div>
            <label class="label">Password {{ $u ? '(kosongkan jika tidak diganti)' : '' }}</label>
            <input type="password" name="password" autocomplete="new-password" @required(! $u) class="input">
            <p class="hint">Minimal 8 karakter, kombinasi huruf dan angka.</p>
        </div>

        <div class="flex gap-3">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-soft">Batal</a>
        </div>
    </form>
@endsection
