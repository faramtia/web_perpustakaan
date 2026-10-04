@extends('pnl.layouts.admin')
@section('title','Event')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Event','sub'=>'Kelola event dan pendaftaran peserta','rows'=>$event,'action'=>'event','readonly'=>!$isAdmin,'cols'=>[['Judul','judul'],['Mulai','tanggal_mulai'],['Selesai','tanggal_selesai'],['Lokasi','lokasi'],['Kuota','kuota'],['Peserta','peserta_count']],'fields'=>[['judul','Judul event','text',12],['deskripsi','Deskripsi','textarea',12],['tanggal_mulai','Tanggal mulai','date'],['tanggal_selesai','Tanggal selesai','date'],['lokasi','Lokasi'],['kuota','Kuota','number']],'extra'=>'pnl.partials.event_actions'])
@endsection
