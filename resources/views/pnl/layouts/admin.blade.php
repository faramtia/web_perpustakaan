@extends('pnl.layouts.app')
@section('body')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<div class="layout">
<aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo Politeknik Negeri Lhokseumawe">
      <div>
        <div class="brand-name">Politeknik Negeri<br>Lhokseumawe</div>
        <div class="brand-sub">Digital Library — Admin</div>
      </div>
    </div>

    <nav class="nav">
<a href="{{ url('/dashboard') }}" class="nav-single {{ request()->is('dashboard') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9 21v-6h6v6"/></svg>
        Dashboard
      </a>
@if($isAdmin)<div class="nav-group {{ request()->is('ejurnal','kategori','koleksi','koleksi/create') ? 'open' : '' }}">
        <button class="group-toggle" onclick="toggleGroup(this)">
          <span class="left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Koleksi
          </span>
          <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="submenu">
          <a href="{{ url('/koleksi/create') }}" class="{{ request()->is('koleksi/create') ? 'current' : '' }}">Tambah Buku</a>
          <a href="{{ url('/koleksi') }}" class="{{ request()->is('koleksi') ? 'current' : '' }}">Data Buku</a>
          <a href="{{ url('/kategori') }}" class="{{ request()->is('kategori') ? 'current' : '' }}">Kategori</a>
          <a href="{{ url('/ejurnal') }}" class="{{ request()->is('ejurnal') ? 'current' : '' }}">E-Jurnal</a>
        </div>
      </div>@endif
@if($isAdmin)<div class="nav-group {{ request()->is('users','users/create') ? 'open' : '' }}">
        <button class="group-toggle" onclick="toggleGroup(this)">
          <span class="left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Pengguna
          </span>
          <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="submenu">
          <a href="{{ url('/users') }}" class="{{ request()->is('users') ? 'current' : '' }}">Manajemen User</a>
          <a href="{{ url('/users/create') }}" class="{{ request()->is('users/create') ? 'current' : '' }}">Tambah User</a>
        </div>
      </div>@endif
@if($isAdmin)<a href="{{ url('/akses') }}" class="nav-single {{ request()->is('akses') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10H12V2z"/><path d="M21.2 8.4A10 10 0 0 0 15.6 2.8V12h9.4"/></svg>
        Hak Akses Admin
      </a>@endif
<div class="nav-group {{ request()->is('peminjaman','reservasi') ? 'open' : '' }}">
        <button class="group-toggle" onclick="toggleGroup(this)">
          <span class="left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            Transaksi
          </span>
          <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="submenu">
          <a href="{{ url('/peminjaman') }}" class="{{ request()->is('peminjaman') ? 'current' : '' }}">Peminjaman Buku</a>
          <a href="{{ url('/reservasi') }}" class="{{ request()->is('reservasi') ? 'current' : '' }}">Reservasi</a>
        </div>
      </div>
@if($isAdmin)<div class="nav-group {{ request()->is('laporan/peminjaman','laporan/pengunjung') ? 'open' : '' }}">
        <button class="group-toggle" onclick="toggleGroup(this)">
          <span class="left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            Laporan
          </span>
          <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="submenu">
          <a href="{{ url('/laporan/pengunjung') }}" class="{{ request()->is('laporan/pengunjung') ? 'current' : '' }}">Pengunjung</a>
          <a href="{{ url('/laporan/peminjaman') }}" class="{{ request()->is('laporan/peminjaman') ? 'current' : '' }}">Peminjaman</a>
        </div>
      </div>@endif
<a href="{{ url('/event') }}" class="nav-single {{ request()->is('event') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Event
      </a>
@if($isAdmin)<a href="{{ url('/artikel') }}" class="nav-single {{ request()->is('artikel') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Artikel &amp; News
      </a>@endif
<a href="{{ url('/tugas-akhir') }}" class="nav-single {{ request()->is('tugas-akhir') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Tugas Akhir
      </a>
<a href="{{ url('/feedback') }}" class="nav-single {{ request()->is('feedback') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Feedback / Survei
      </a>
<a href="{{ url('/absensi') }}" class="nav-single {{ request()->is('absensi') ? 'active' : '' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Absensi
      </a>
@if($isAdmin)<div class="nav-group {{ request()->is('pengaturan') ? 'open' : '' }}">
        <button class="group-toggle" onclick="toggleGroup(this)">
          <span class="left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9c.31-.6.27-1.31-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06c.51.51 1.22.55 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09c0 .68.39 1.29 1 1.51.6.22 1.31.18 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06c-.51.51-.55 1.22-.33 1.82V9c.22.6.83 1 1.51 1H21a2 2 0 0 1 0 4h-.09c-.68 0-1.29.39-1.51 1z"/></svg>
            Pengaturan
          </span>
          <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="submenu">
          <a href="{{ url('/pengaturan') }}#informasi" class="">Informasi Perpus</a>
          <a href="{{ url('/pengaturan') }}#branding" class="">Logo &amp; Branding</a>
          <a href="{{ url('/pengaturan') }}#contact" class="">Contact Us</a>
          <a href="{{ url('/pengaturan') }}#kategori" class="">Kategori Buku</a>
          <a href="{{ url('/pengaturan') }}#koleksi" class="">Pengaturan Koleksi</a>
          <a href="{{ url('/pengaturan') }}#aturan" class="">Aturan Peminjaman &amp; Denda</a>
        </div>
      </div>@endif</nav>

    <div class="sidebar-footer">
      <form method="POST" action="{{ url('/logout') }}">@csrf<button class="logout-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
        Log Out
      </button></form>
    </div>
  </aside>
<div class="main">
<header class="topbar">
      <div class="topbar-left">
        <button class="menu-btn" onclick="document.getElementById('sidebar').classList.toggle('open')">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="crumb">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5"/><path d="M5 9.5V21h14V9.5"/></svg>
          <span>@yield('title')</span>
        </div>
      </div>
      <div class="topbar-right">
        <button class="icon-btn" aria-label="Cari">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </button>
        <button class="icon-btn" aria-label="Notifikasi">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
          <span class="dot-badge"></span>
        </button>
        <div class="admin-chip">
          <div class="avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'AD',0,2)) }}</div>
          <div class="who">
            <div class="name">{{ auth()->user()?->name ?? 'Admin' }}</div>
            <div class="role">{{ ucfirst($role) }}</div>
          </div>
        </div>
      </div>
    </header>
<main class="content">
@yield('content')
<footer class="foot">Perpustakaan Digital &middot; Politeknik Negeri Lhokseumawe</footer>
</main>
</div>
</div>
@endsection
