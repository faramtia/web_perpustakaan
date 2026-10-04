@extends('pnl.layouts.app')
@section('bodyclass','pub')
@section('body')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<nav class="navbar navbar-expand-lg pub-nav sticky-top" data-bs-theme="dark"><div class="container">
 <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}"><img src="{{ asset('images/logo-pnl.png') }}" height="36" alt="Logo"><span class="fs-6 fw-semibold">{{ $lib['nama'] }}</span></a>
 <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nv" aria-label="Menu"><span class="navbar-toggler-icon"></span></button>
 <div id="nv" class="collapse navbar-collapse"><ul class="navbar-nav ms-auto align-items-lg-center">
 @foreach(['catalog'=>'Catalog','service'=>'Service','guides'=>'Library guides','ejurnal'=>'E-Jurnal','gallery'=>'Gallery','news'=>'News & info','about'=>'About'] as $id=>$t)
  <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#{{ $id }}">{{ $t }}</a></li>
 @endforeach
 <li class="nav-item ms-lg-2"><a class="btn btn-pnl btn-sm" href="{{ url('/login') }}">Login</a></li></ul></div>
</div></nav>
@yield('content')
<footer class="foot py-4 text-center small" style="color:var(--muted)">© {{ date('Y') }} {{ $lib['nama'] }}</footer>
@endsection
