@extends('pnl.layouts.admin')
@section('title','Reservasi Buku')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Reservasi Buku','sub'=>'Antrean buku yang sedang habis','rows'=>$reservasi,'action'=>'reservasi','readonly'=>false,'cols'=>[['Pemesan','user.name'],['Buku','buku.judul'],['Tanggal','tanggal_reservasi'],['Status','status','badge']],'fields'=>$f_resv,'extra'=>'pnl.partials.reservasi_actions'])
@endsection
