@extends('pnl.layouts.admin')
@section('title','Dashboard')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<div class="page-head"><div><h1>Selamat Datang, {{ auth()->user()?->name ?? 'Admin' }}!</h1><p>Kelola koleksi, layanan pengguna, dan aktivitas perpustakaan secara real-time.</p></div>
<div class="datebox"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><div><div class="dnum">{{ now()->translatedFormat('l, d F Y') }}</div><div style="color:var(--muted)">Pukul {{ now()->format('H.i') }} WIB</div></div></div></div>
<section class="stats-row">
<div class="stat-card tone-orange"><div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><div class="label">Total Buku</div><div class="value">{{ number_format($stat['buku'],0,',','.') }}</div></div>
<div class="stat-card tone-blue"><div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="label">Anggota</div><div class="value">{{ number_format($stat['anggota'],0,',','.') }}</div></div>
<div class="stat-card tone-green"><div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg></div><div class="label">Buku Dipinjam</div><div class="value">{{ number_format($stat['dipinjam'],0,',','.') }}</div></div>
<div class="stat-card tone-red"><div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div><div class="label">Terlambat</div><div class="value">{{ number_format($stat['terlambat'],0,',','.') }}</div></div>
<div class="stat-card tone-gold"><div class="icon-wrap"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></div><div class="label">Total Denda</div><div class="value">{{ 'Rp '.number_format($stat['denda'],0,',','.') }}</div></div>
</section>
@php $tot=max(1,array_sum($koleksi)); $a=round($koleksi['tersedia']/$tot*100); $b=$a+round($koleksi['dipinjam']/$tot*100); $mx=max(array_column($grafik,1)); @endphp
<section class="row-3">
 <div class="card"><div class="card-head"><h3>Grafik Peminjaman Buku</h3><select class="pill-select"><option>Minggu Ini</option><option>Bulan Ini</option></select></div>
  <div class="bars">@foreach($grafik as $g)<div class="bar-col {{ $g[1]==$mx?'peak':'' }}"><div class="bar" style="height:{{ $g[1] }}%"></div><span>{{ $g[0] }}</span></div>@endforeach</div></div>
 <div class="card"><div class="card-head"><h3>Status Koleksi</h3></div><div class="donut-wrap">
  <div class="donut" style="background:conic-gradient(var(--primary) 0 {{ $a }}%, var(--gold) {{ $a }}% {{ $b }}%, var(--border) {{ $b }}% 100%)"><div class="donut-center"><span class="n">{{ number_format($tot,0,',','.') }}</span><span class="t">Buku</span></div></div>
  <div class="legend">@foreach([['Tersedia','tersedia','var(--primary)'],['Dipinjam','dipinjam','var(--gold)'],['Rusak/Hilang','lain','var(--border)']] as $l)<div class="legend-item"><span class="left"><span class="swatch" style="background:{{ $l[2] }}"></span>{{ $l[0] }}</span><span><b>{{ number_format($koleksi[$l[1]],0,',','.') }}</b> <span class="pct">{{ round($koleksi[$l[1]]/$tot*100) }}%</span></span></div>@endforeach</div></div></div>
 <div class="card"><div class="card-head"><h3>Quick Actions</h3></div><div class="qa-grid">
<a href="{{ url('/koleksi/create') }}" class="qa-tile"><div class="qi" style="background:var(--warning-bg);color:var(--primary-dark)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><span>Tambah Buku</span></a>
<a href="{{ url('/users') }}" class="qa-tile"><div class="qi" style="background:var(--info-bg);color:var(--info)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><span>Kelola User</span></a>
<a href="{{ url('/event') }}" class="qa-tile"><div class="qi" style="background:var(--success-bg);color:var(--success)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><span>Buat Event</span></a>
<a href="{{ url('/laporan/peminjaman') }}" class="qa-tile"><div class="qi" style="background:#FFF3D2;color:var(--gold-deep)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg></div><span>Generate Laporan</span></a>
</div></div>
</section>
<section class="row-2">
 <div class="card"><div class="card-head"><h3>Transaksi Terbaru</h3><a class="link-more" href="{{ url('/laporan/peminjaman') }}">Lihat Semua →</a></div>
  <div style="overflow-x:auto"><table><thead><tr><th>No</th><th>Tanggal</th><th>Jenis</th><th>Judul Buku</th><th>Nama Anggota</th><th>Status</th></tr></thead><tbody>
  @foreach(array_slice($peminjaman,0,5) as $p)<tr><td>{{ $loop->iteration }}</td><td>{{ $p['tanggal_pinjam'] }}</td><td>Peminjaman</td><td>{{ data_get($p,'detail_peminjaman.0.buku.judul') }}</td><td>{{ data_get($p,'user.name') }}</td><td><span class="badge {{ $badge($p['status']) }}">{{ ucfirst($p['status']) }}</span></td></tr>@endforeach
  </tbody></table></div></div>
 <div class="side-col">
  <div class="card"><div class="card-head"><h3>Notifikasi</h3></div>
   @foreach($notif as $n)<div class="notif-item"><div class="notif-ic" style="background:var(--{{ $n[0] }}-bg);color:var(--{{ $n[0] }})">{{ $n[1] }}</div><div class="notif-text"><p>{{ $n[2] }}</p><span>{{ $n[3] }}</span></div></div>@endforeach</div>
  <div class="card"><div class="card-head"><h3>Event Mendatang</h3><a class="link-more" href="{{ url('/event') }}">Lihat Semua →</a></div>
   @foreach(array_slice($event,0,2) as $e)<div class="event-card mb-2"><div class="event-date"><div class="dd">{{ \Carbon\Carbon::parse($e['tanggal_mulai'])->format('d') }}</div><div class="mm">{{ \Carbon\Carbon::parse($e['tanggal_mulai'])->translatedFormat('M') }}</div></div><div class="event-info"><p>{{ $e['judul'] }}</p><span>📍 {{ $e['lokasi'] }}</span><span style="color:var(--primary-dark)">{{ $e['peserta_count'] }} peserta terdaftar</span></div></div>@endforeach</div>
 </div>
</section>
@endsection
