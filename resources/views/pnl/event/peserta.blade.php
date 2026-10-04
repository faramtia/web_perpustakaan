@extends('pnl.layouts.admin')
@section('title','Peserta Event')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
@include('pnl.partials.head', ['title'=>'Peserta: '.$eventDetail['judul'],'sub'=>$eventDetail['tanggal_mulai'].' · '.$eventDetail['lokasi'].' · kuota '.$eventDetail['kuota']])
<a href="{{ url('event') }}" class="btn btn-sm btn-outline-secondary mb-3">← Kembali ke event</a>
<div class="card"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>No</th><th>Nama</th><th>NIM / NIP</th><th>Tanggal daftar</th><th>Status</th>@if($isAdmin)<th></th>@endif</tr></thead><tbody>
@forelse($event_peserta as $p)<tr><td>{{ $loop->iteration }}</td><td>{{ data_get($p,'user.name') }}</td><td>{{ data_get($p,'user.nim_nip') }}</td><td>{{ $p['tanggal_daftar'] }}</td><td><span class="badge {{ $badge($p['status_pendaftaran']) }}">{{ $p['status_pendaftaran'] }}</span></td>
@if($isAdmin)<td><form method="POST" action="{{ url('event/'.$eventDetail['id'].'/peserta/'.$p['id']) }}" class="d-inline-flex gap-1">@csrf @method('PATCH')<select name="status_pendaftaran" class="form-select form-select-sm">@foreach(['menunggu','diterima','hadir'] as $s)<option @selected($p['status_pendaftaran']===$s)>{{ $s }}</option>@endforeach</select><button class="btn btn-sm btn-pnl">Simpan</button></form></td>@endif</tr>
@empty<tr><td colspan="6" class="text-center text-muted">Belum ada peserta.</td></tr>@endforelse</tbody></table></div></div>
@endsection
