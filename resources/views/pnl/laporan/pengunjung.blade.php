@extends('pnl.layouts.admin')
@section('title','Laporan Pengunjung')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<form class="row g-2 mb-3" method="GET"><div class="col-auto"><input type="date" name="dari" class="form-control" value="{{ request('dari') }}"></div><div class="col-auto"><input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}"></div><div class="col-auto"><button class="btn btn-pnl">Terapkan</button></div></form>
@include('pnl.partials.crud', ['title'=>'Laporan Pengunjung','sub'=>'Data kunjungan dari absensi','rows'=>$absensi,'action'=>'laporan/pengunjung','readonly'=>true,'cols'=>[['Nama','user.name'],['NIM / NIP','user.nim_nip'],['Tanggal','tanggal'],['Masuk','waktu_masuk'],['Keluar','waktu_keluar'],['Metode','metode','badge']],'fields'=>[]])
@endsection
