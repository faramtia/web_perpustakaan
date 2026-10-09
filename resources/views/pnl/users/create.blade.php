@extends('pnl.layouts.admin')
@section('title','Tambah User')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.head', ['title'=>'Tambah User','sub'=>'Daftarkan pengguna baru'])
<div class="card"><form method="POST" action="{{ url('users') }}" enctype="multipart/form-data">@csrf
@include('pnl.partials.fields', ['fields'=>$f_user])
<div class="mt-3"><button class="btn btn-pnl">Simpan</button> <a href="{{ url('users') }}" class="btn btn-outline-secondary">Batal</a></div></form></div>
@endsection
