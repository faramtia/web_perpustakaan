@extends('pnl.layouts.admin')
@section('title','Absensi')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<div class="card mb-3"><div class="d-flex flex-wrap gap-2 align-items-center"><form method="POST" action="{{ url('absensi') }}" class="d-flex gap-2">@csrf<select name="metode" class="form-select" style="width:140px"><option value="manual">Manual</option><option value="qr">QR</option></select><button class="btn btn-pnl">Absen masuk</button></form><form method="POST" action="{{ url('absensi/keluar') }}">@csrf @method('PATCH')<button class="btn btn-outline-secondary">Absen keluar</button></form></div></div>
@include('pnl.partials.crud', ['title'=>'Absensi','sub'=>'Catat kehadiran pengunjung','rows'=>$absensi,'action'=>'absensi','readonly'=>true,'cols'=>[['Nama','user.name'],['Tanggal','tanggal'],['Masuk','waktu_masuk'],['Keluar','waktu_keluar'],['Metode','metode','badge']],'fields'=>[]])
@endsection
