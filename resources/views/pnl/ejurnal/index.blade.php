@extends('pnl.layouts.admin')
@section('title','E-Jurnal')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'E-Jurnal','sub'=>'Koleksi jurnal elektronik','rows'=>$ejurnal,'action'=>'ejurnal','readonly'=>false,'cols'=>[['Judul','judul'],['Penulis','penulis'],['Kategori','kategori.nama_kategori'],['Tahun','tahun'],['Rak','lokasi_rak']],'fields'=>$f_ej])
@endsection
