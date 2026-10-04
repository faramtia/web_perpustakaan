@extends('pnl.layouts.admin')
@section('title','Peminjaman Buku')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Peminjaman Buku','sub'=>'Ajukan, setujui, dan kembalikan buku (tabel peminjaman + detail_peminjaman)','rows'=>$peminjaman,'action'=>'peminjaman','readonly'=>false,'cols'=>[['Peminjam','user.name'],['Buku','detail_peminjaman.0.buku.judul'],['Jml','detail_peminjaman.0.jumlah'],['Pinjam','tanggal_pinjam'],['Jatuh tempo','tanggal_jatuh_tempo'],['Kembali','tanggal_kembali'],['Petugas','petugas.name'],['Denda','detail_peminjaman.0.denda'],['Status','status','badge']],'fields'=>$f_pinjam,'extra'=>'pnl.partials.pinjam_actions'])
@endsection
