@extends('pnl.layouts.admin')
@section('title','Tugas Akhir')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Tugas Akhir','sub'=>'Unggah dan review tugas akhir mahasiswa','rows'=>$tugas_akhir,'action'=>'tugas-akhir','readonly'=>false,'cols'=>[['Mahasiswa','user.name'],['Judul','judul'],['Reviewer','reviewer.name'],['Status','status','badge'],['Catatan','catatan_reviewer']],'fields'=>$f_ta,'extra'=>'pnl.partials.ta_actions'])
@endsection
