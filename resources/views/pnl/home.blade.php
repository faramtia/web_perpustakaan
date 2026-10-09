@extends('pnl.layouts.public')
@section('title','Beranda')
@section('content')
@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<header class="hero"><div class="container">
 <h1 class="display-5 fw-bold" style="font-family:Lexend">Temukan bukunya,<br>pinjam hari ini.</h1>
 <p class="lead col-lg-6">Cari judul, penulis, penerbit, atau ISBN. Masuk untuk meminjam, memperpanjang, dan mengunggah karya ilmiah.</p>
 <div class="input-group" style="max-width:620px"><input id="cari" class="form-control form-control-lg" placeholder="Cari judul, penulis, penerbit, ISBN" aria-label="Cari buku"><a href="#catalog" class="btn btn-warning fw-semibold">Cari buku</a></div>
</div></header>
<section id="catalog" class="sec"><div class="container"><h2 style="font-family:Lexend">Catalog</h2>
 <div class="table-responsive"><table class="table align-middle" id="tb"><thead><tr><th>ISBN / kode</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tipe</th><th>Stok</th></tr></thead><tbody>
 @foreach($buku as $b)<tr><td>{{ $b['isbn'] }}</td><td>{{ $b['judul'] }}</td><td>{{ $b['penulis'] }}</td><td>{{ $b['penerbit'] }}</td><td>{{ data_get($b,'tipe_koleksi.nama_tipe') }}</td>
 <td>@if($b['stok']>0)<span class="badge b-green">{{ $b['stok'] }} tersedia</span>@else<span class="badge b-red">Habis</span>@endif</td></tr>@endforeach
 </tbody></table></div></div></section>
<section id="service" class="sec"><div class="container"><h2 style="font-family:Lexend">Service</h2><div class="row g-3">
 @foreach([['Unggah jurnal & skripsi','Layanan akademik. Kirim karya ilmiah untuk dimasukkan ke repositori.','tugas-akhir'],['Pembayaran denda online','Layanan administrasi. Lunasi denda tanpa antre di loket.','peminjaman'],['Fasilitas','Area multimedia, BI corner, dan ruang diskusi.','event']] as $s)
 <div class="col-md-4"><div class="card h-100"><h5>{{ $s[0] }}</h5><p class="text-muted">{{ $s[1] }}</p><a href="{{ url($s[2]) }}" class="btn btn-sm btn-pnl align-self-start mt-auto">Buka</a></div></div>@endforeach
</div></div></section>
<section id="guides" class="sec"><div class="container"><h2 style="font-family:Lexend">Library guides</h2><div class="accordion" id="ac">
 @foreach(['Peminjaman'=>'Login, pilih buku di katalog, lalu ambil di meja sirkulasi. Maksimal '.$lib['maks_pinjam'].' buku selama '.$lib['lama_pinjam'].' hari.','Perpanjang'=>'Perpanjang sebelum jatuh tempo, selama tidak ada reservasi.','Pengembalian'=>'Kembalikan buku di meja sirkulasi atau drop box.','Pembayaran denda'=>'Denda Rp'.number_format($lib['denda_per_hari'],0,',','.').' per hari. Bayar online atau di loket.','Akses karya ilmiah'=>'Skripsi dan jurnal kampus dapat dibaca setelah login.','Akses e-book'=>'Koleksi e-book tersedia untuk anggota aktif.','Scopus, skripsi'=>'Cari artikel Scopus dan skripsi lewat portal e-resource.','Akses e-resource'=>'Gunakan akun anggota untuk basis data langganan.'] as $t=>$i)
 <div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#g{{ $loop->index }}">{{ $t }}</button></h3><div id="g{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#ac"><div class="accordion-body text-muted">{{ $i }}</div></div></div>@endforeach
</div></div></section>
<section id="ejurnal" class="sec"><div class="container"><h2 style="font-family:Lexend">E-Jurnal</h2><div class="accordion" id="aj">
 @foreach($ejurnal as $j)<div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#j{{ $loop->index }}">{{ $j['judul'] }}&nbsp;<small class="text-muted">{{ $j['penulis'] }} · {{ $j['tahun'] }}</small></button></h3>
 <div id="j{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#aj"><div class="accordion-body text-muted">{{ $j['abstrak'] }}<br><small>Rak: {{ $j['lokasi_rak'] }} · {{ data_get($j,'kategori.nama_kategori') }}</small></div></div></div>@endforeach
</div></div></section>
<section id="gallery" class="sec"><div class="container"><h2 style="font-family:Lexend">Gallery</h2><div class="row g-3">
 {{-- Belum ada tabel galeri di database. Ganti blok warna dengan <img src="{{ asset('images/gallery/xxx.jpg') }}"> --}}
 @foreach(['Ruang baca','Rak koleksi','Area multimedia','Ruang diskusi','BI corner','Meja sirkulasi'] as $g)<div class="col-6 col-md-4"><div class="tile" style="background:hsl({{ 20+$loop->index*18 }},60%,{{ 30+($loop->index%3)*6 }}%)">{{ $g }}</div></div>@endforeach
</div></div></section>
<section id="news" class="sec"><div class="container"><h2 style="font-family:Lexend">News &amp; info</h2><div class="mb-3 d-flex flex-wrap gap-2" id="fk"><button class="btn btn-sm btn-pnl" data-k="">Semua</button><button class="btn btn-sm btn-outline-secondary" data-k="artikel">Artikel</button><button class="btn btn-sm btn-outline-secondary" data-k="book_review">Book review</button><button class="btn btn-sm btn-outline-secondary" data-k="ta_exposure">TA exposure</button></div><div class="row g-3">
 @foreach($artikel as $a)<div class="col-md-6 art" data-k="{{ $a['kategori'] }}"><div class="card h-100"><span class="badge b-amber align-self-start mb-2">{{ str_replace('_',' ',$a['kategori']) }}</span><h5>{{ $a['judul'] }}</h5><p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit($a['konten'],120) }}</p></div></div>@endforeach
</div></div></section>
<section id="about" class="sec"><div class="container"><h2 style="font-family:Lexend">About</h2><p class="col-lg-7">{{ $lib['nama'] }} melayani civitas akademika dengan koleksi buku, e-book, e-jurnal, dan karya ilmiah.<br>{{ $lib['alamat'] }} · {{ $lib['telp'] }} · {{ $lib['email'] }}</p></div></section>
@endsection
@push('scripts')<script>document.getElementById('cari').addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#tb tbody tr').forEach(r=>r.hidden=!r.textContent.toLowerCase().includes(q))})</script>@endpush

@push('scripts')<script>document.querySelectorAll('#fk button').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('#fk button').forEach(x=>x.className='btn btn-sm btn-outline-secondary');b.className='btn btn-sm btn-pnl';document.querySelectorAll('.art').forEach(a=>a.hidden=b.dataset.k&&a.dataset.k!==b.dataset.k)}))</script>@endpush
