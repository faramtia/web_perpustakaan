@extends('pnl.layouts.admin')
@section('title','Pengaturan')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.head', ['title'=>'Pengaturan','sub'=>'Informasi perpustakaan, branding, koleksi, dan aturan'])
<ul class="nav nav-pills mb-3 flex-wrap gap-1" id="tabs">
@foreach(['informasi'=>'Informasi Perpus','branding'=>'Logo & Branding','contact'=>'Contact Us','kategori'=>'Kategori Buku','koleksi'=>'Pengaturan Koleksi','aturan'=>'Aturan Peminjaman & Denda'] as $id=>$t)
 <li class="nav-item"><button class="nav-link {{ $loop->first?'active':'' }}" data-bs-toggle="pill" data-bs-target="#p-{{ $id }}">{{ $t }}</button></li>
@endforeach</ul>
<div class="card"><div class="tab-content">
 <div class="tab-pane fade show active" id="p-informasi"><form method="POST" action="{{ url('pengaturan/informasi') }}">@csrf<div class="row g-3"><div class="col-md-6"><label class="form-label small fw-semibold">Nama perpustakaan</label><input name="nama" class="form-control" value="{{ $lib['nama'] }}"></div><div class="col-md-6"><label class="form-label small fw-semibold">Alamat</label><input name="alamat" class="form-control" value="{{ $lib['alamat'] }}"></div></div><button class="btn btn-pnl mt-3">Simpan</button></form></div>
 <div class="tab-pane fade" id="p-branding"><form method="POST" action="{{ url('pengaturan/branding') }}" enctype="multipart/form-data">@csrf<label class="form-label small fw-semibold">Logo</label><input type="file" name="logo" accept="image/*" class="form-control mb-3" style="max-width:420px"><img src="{{ asset('images/logo-pnl.png') }}" height="64" alt="Logo saat ini" class="d-block mb-3"><button class="btn btn-pnl">Simpan logo</button></form></div>
 <div class="tab-pane fade" id="p-contact"><form method="POST" action="{{ url('pengaturan/contact') }}">@csrf<div class="row g-3"><div class="col-md-6"><label class="form-label small fw-semibold">Email</label><input name="email" class="form-control" value="{{ $lib['email'] }}"></div><div class="col-md-6"><label class="form-label small fw-semibold">Telepon</label><input name="telp" class="form-control" value="{{ $lib['telp'] }}"></div></div><button class="btn btn-pnl mt-3">Simpan</button></form></div>
 <div class="tab-pane fade" id="p-kategori">@include('pnl.partials.simple', ['rows'=>$kategori,'key'=>'nama_kategori','label'=>'Kategori','action'=>'kategori'])</div>
 <div class="tab-pane fade" id="p-koleksi"><div class="row g-4"><div class="col-md-6"><h3 class="h6">Tipe koleksi</h3>@include('pnl.partials.simple', ['rows'=>$tipe_koleksi,'key'=>'nama_tipe','label'=>'Tipe','action'=>'tipe-koleksi'])</div><div class="col-md-6"><h3 class="h6">Lokasi / ruang</h3>@include('pnl.partials.simple', ['rows'=>$lokasi,'key'=>'nama_ruang','label'=>'Ruang','action'=>'lokasi'])</div></div></div>
 <div class="tab-pane fade" id="p-aturan"><form method="POST" action="{{ url('pengaturan/aturan') }}">@csrf<div class="row g-3">@foreach(['maks_pinjam'=>'Maks. buku dipinjam','lama_pinjam'=>'Lama pinjam (hari)','denda_per_hari'=>'Denda per hari (Rp)'] as $k=>$t)<div class="col-md-4"><label class="form-label small fw-semibold">{{ $t }}</label><input type="number" name="{{ $k }}" class="form-control" value="{{ $lib[$k] }}"></div>@endforeach</div><button class="btn btn-pnl mt-3">Simpan aturan</button></form></div>
</div></div>
@push('scripts')<script>const h=location.hash.slice(1);if(h){const b=document.querySelector('[data-bs-target="#p-'+h+'"]');b&&bootstrap.Tab.getOrCreateInstance(b).show()}</script>@endpush
@endsection
