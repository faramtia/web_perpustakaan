@extends('pnl.layouts.admin')
@section('title','Feedback / Survei')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Feedback / Survei','sub'=>'Keluhan, saran, dan tanya pustakawan','rows'=>$feedback,'action'=>'feedback','readonly'=>false,'cols'=>[['Dari','user.name'],['Jenis','jenis'],['Isi','isi'],['Balasan','balasan'],['Status','status','badge']],'fields'=>[['jenis','Jenis','select',6,$en(['keluhan','saran','tanya_pustakawan'])],['status','Status (petugas)','select',6,$en(['baru','diproses','selesai'])],['isi','Isi','textarea',12],['balasan','Balasan (petugas)','textarea',12]]])
@endsection
