@extends('pnl.layouts.admin')
@section('title','Hak Akses Admin')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.crud', ['title'=>'Hak Akses Admin','sub'=>'Jenis user (tabel jenis_user)','rows'=>$jenis_user,'action'=>'akses','cols'=>[['Role','nama_role'],['Deskripsi','deskripsi']],'fields'=>[['nama_role','Nama role','text',6],['deskripsi','Deskripsi','text',6]]])
<div class="card mt-3"><div class="card-head"><h3>Matriks akses menu</h3></div>
<form method="POST" action="{{ url('akses/matriks') }}">@csrf
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Menu</th>@foreach($jenis_user as $j)<th class="text-center">{{ $j['nama_role'] }}</th>@endforeach</tr></thead><tbody>
@foreach(['Dashboard','Koleksi','Pengguna','Laporan','Event','Feedback / Survei','Absensi','Pengaturan'] as $m)
<tr><td>{{ $m }}</td>@foreach($jenis_user as $j)<td class="text-center"><input type="checkbox" class="form-check-input" name="akses[{{ $j['id'] }}][]" value="{{ $m }}" @checked(in_array($j['nama_role'],['admin']) || in_array($m,['Dashboard','Event','Feedback / Survei','Absensi']))></td>@endforeach</tr>
@endforeach</tbody></table></div>
<p class="small text-muted">Catatan: database belum punya tabel hak akses per menu. Tampilan ini perlu tabel tambahan dari tim backend.</p>
<button class="btn btn-pnl">Simpan hak akses</button></form></div>
@endsection
