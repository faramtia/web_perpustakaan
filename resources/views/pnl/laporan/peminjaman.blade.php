@extends('pnl.layouts.admin')
@section('title','Laporan Peminjaman')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<div class="stats-row" style="grid-template-columns:repeat(3,1fr)"><div class="stat-card tone-orange"><div class="label">Total transaksi</div><div class="value">{{ count($peminjaman) }}</div></div><div class="stat-card tone-red"><div class="label">Terlambat</div><div class="value">{{ collect($peminjaman)->where('status','terlambat')->count() }}</div></div><div class="stat-card tone-gold"><div class="label">Total denda</div><div class="value">Rp {{ number_format(collect($peminjaman)->sum(fn($p)=>data_get($p,'detail_peminjaman.0.denda',0)),0,',','.') }}</div></div></div>
<form class="row g-2 mb-3" method="GET"><div class="col-auto"><select name="status" class="form-select"><option value="">Semua status</option>@foreach(['pending','aktif','ditolak','dikembalikan','terlambat'] as $s)<option @selected(request('status')===$s)>{{ $s }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-pnl">Filter</button></div></form>
@include('pnl.partials.crud', ['title'=>'Laporan Peminjaman','sub'=>'Riwayat peminjaman, status, dan denda','rows'=>$peminjaman,'action'=>'laporan/peminjaman','readonly'=>true,'cols'=>[['Peminjam','user.name'],['Buku','detail_peminjaman.0.buku.judul'],['Pinjam','tanggal_pinjam'],['Jatuh tempo','tanggal_jatuh_tempo'],['Kembali','tanggal_kembali'],['Denda','detail_peminjaman.0.denda'],['Status','status','badge']],'fields'=>[]])
@endsection
