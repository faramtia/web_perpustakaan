@extends('layouts.app')
@section('title', 'Tanya Pustakawan')

@section('content')
@php $staff = auth()->user()->isStaff(); @endphp

    <x-page-header icon="bi-chat-dots" :title="$staff ? 'Feedback & Tanya Pustakawan' : 'Tanya Pustakawan'"
        :subtitle="$staff ? 'Baca masukan dari anggota dan balas langsung.' : 'Kirim pertanyaan, saran, atau keluhan ke pustakawan.'" />

    @unless ($staff)
        <form method="POST" action="{{ route('anggota.feedback.store') }}" class="card mb-6 space-y-3 p-5">
            @csrf
            <h2 class="font-bold">Kirim pesan baru</h2>
            <select name="jenis" required class="input sm:w-64">
                @foreach ($jenisList as $nilai => $label)
                    <option value="{{ $nilai }}" @selected(old('jenis') === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
            <textarea name="isi" rows="3" required maxlength="2000" placeholder="Tulis pertanyaan atau masukanmu..." class="input">{{ old('isi') }}</textarea>
            <button class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim</button>
        </form>
    @endunless

    <x-status-tabs :options="['Belum Dibalas' => 'Belum Dibalas', 'Sudah Dibalas' => 'Sudah Dibalas']" />

    <div class="space-y-4">
        @forelse ($feedback as $f)
            <div class="card space-y-3 p-5">
                <div class="flex items-center justify-between gap-3 border-b border-stone-100 pb-3">
                    <div class="flex items-center gap-3">
                        <x-user-cell :user="$f->user" :sub="optional($f->tanggal)->translatedFormat('d M Y')" />
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="chip bg-stone-100 text-stone-600">{{ $jenisList[$f->jenis] ?? ucfirst(str_replace('_', ' ', $f->jenis ?? 'umum')) }}</span>
                        <x-status-badge :status="$f->status" />
                    </div>
                </div>

                <p class="rounded-xl bg-stone-50 p-4 text-sm text-stone-700">{{ $f->isi }}</p>

                @if ($f->balasan)
                    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm">
                        <p class="mb-1 font-bold text-yellow-900"><i class="bi bi-reply-fill"></i> Balasan pustakawan</p>
                        <p class="text-stone-800">{{ $f->balasan }}</p>
                    </div>
                @endif

                @if ($staff)
                    <form method="POST" action="{{ route('petugas.feedback.balas', $f) }}" class="space-y-2">
                        @csrf
                        <textarea name="balasan" rows="2" required maxlength="2000" placeholder="Tulis balasan..." class="input">{{ $f->balasan }}</textarea>
                        <button class="btn btn-sm btn-primary"><i class="bi bi-{{ $f->balasan ? 'arrow-repeat' : 'send-fill' }}"></i> {{ $f->balasan ? 'Perbarui Balasan' : 'Kirim Balasan' }}</button>
                    </form>
                @endif
            </div>
        @empty
            <x-empty-state icon="bi-chat-square-text" title="Belum ada pesan" />
        @endforelse
    </div>

    <div class="mt-4">{{ $feedback->links() }}</div>
@endsection
