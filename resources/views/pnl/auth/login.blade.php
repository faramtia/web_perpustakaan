@extends('pnl.layouts.app')
@section('title','Login')
@section('bodyclass','pub')
@section('body')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
 <div class="card" style="width:min(420px,100%)">
  <div class="text-center mb-3"><img src="{{ asset('images/logo-pnl.png') }}" height="64" alt="Logo"><h1 class="h4 mt-2" style="font-family:Lexend">Masuk</h1><p class="text-muted small mb-0">{{ $lib['nama'] }}</p></div>
  {{-- Kirim ke route login milik tim backend --}}
  <form method="POST" action="{{ url('/login') }}">@csrf
   <label class="form-label small fw-semibold" for="l">Email atau NIM/NIP</label><input id="l" name="login" class="form-control mb-3" required autocomplete="username">
   <label class="form-label small fw-semibold" for="p">Password</label><input id="p" name="password" type="password" class="form-control mb-3" required autocomplete="current-password">
   <div class="d-flex gap-2"><button class="btn btn-pnl">Masuk</button><a href="{{ url('/') }}" class="btn btn-outline-secondary">Kembali ke beranda</a></div>
  </form>
 </div>
</div>
@endsection
