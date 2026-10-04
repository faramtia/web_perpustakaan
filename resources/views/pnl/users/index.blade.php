@extends('pnl.layouts.admin')
@section('title','Manajemen User')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Manajemen User','sub'=>'Kelola pengguna dan jenis user','rows'=>$users,'action'=>'users','readonly'=>false,'cols'=>[['Nama','name'],['Email','email'],['NIM / NIP','nim_nip'],['Jenis user','jenis_user.nama_role']],'fields'=>$f_user])
@endsection
