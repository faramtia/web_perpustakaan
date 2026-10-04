@extends('pnl.layouts.admin')
@section('title','Data Buku')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Data Buku','sub'=>'Kelola data buku, e-book, e-jurnal, dan e-TGA','rows'=>$buku,'action'=>'koleksi','readonly'=>false,'cols'=>[['Judul','judul'],['Penulis','penulis'],['Kategori','kategori.nama_kategori'],['Tipe','tipe_koleksi.nama_tipe'],['Lokasi','lokasi.nama_ruang'],['Tahun','tahun'],['Stok','stok']],'fields'=>$f_buku])
@endsection
