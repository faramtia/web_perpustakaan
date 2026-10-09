@extends('pnl.layouts.admin')
@section('title','Artikel & News')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Artikel & News','sub'=>'News & info, book review, TA exposure','rows'=>$artikel,'action'=>'artikel','readonly'=>false,'cols'=>[['Judul','judul'],['Kategori','kategori','badge'],['Terbit','tanggal_terbit'],['Penulis','penulis.name']],'fields'=>[['judul','Judul','text',8],['kategori','Kategori','select',4,$en(['book_review','ta_exposure','artikel'])],['konten','Konten','textarea',12]]])
@endsection
