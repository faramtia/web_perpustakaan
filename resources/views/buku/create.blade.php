@extends('layouts.app')
@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 mb-4 max-w-2xl text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.buku.store') }}" class="bg-white border rounded-xl p-6 max-w-2xl space-y-4">
        @csrf
        @include('buku._form')

        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Simpan Buku
        </button>
    </form>

@endsection