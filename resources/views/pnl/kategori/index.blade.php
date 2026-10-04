@extends('pnl.layouts.admin')
@section('title','Kategori')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Kategori','sub'=>'Kategori buku','rows'=>$kategori,'action'=>'kategori','readonly'=>false,'cols'=>[['Nama kategori','nama_kategori']],'fields'=>[['nama_kategori','Nama kategori','text',12]]])
@endsection
