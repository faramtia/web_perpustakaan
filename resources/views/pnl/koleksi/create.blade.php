@extends('pnl.layouts.admin')
@section('title','Tambah Buku')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.head', ['title'=>'Tambah Buku','sub'=>'Isi data buku baru'])
<div class="card"><form method="POST" action="{{ url('koleksi') }}" enctype="multipart/form-data">@csrf
@include('pnl.partials.fields', ['fields'=>$f_buku])
<div class="mt-3"><button class="btn btn-pnl">Simpan</button> <a href="{{ url('koleksi') }}" class="btn btn-outline-secondary">Batal</a></div></form></div>
@endsection
