@extends('layouts.guest')

@section('title', 'Tentang Kami - Perpustakaan Politeknik Negeri Lhokseumawe')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="text-xs text-stone-500 mb-3 flex items-center gap-1">
        <a href="{{ route('home') }}" class="hover:text-yellow-700">Beranda</a>
        <span>&rsaquo;</span>
        <span class="font-semibold text-stone-700">Tentang Kami</span>
    </nav>

    <h1 class="text-2xl font-bold text-stone-800 mb-4">Tentang Kami</h1>

    {{-- Tabs --}}
    <div class="bg-white rounded-t-xl border border-b-0 px-2 sm:px-4 overflow-x-auto">
        <div class="flex gap-1 text-sm font-medium whitespace-nowrap" id="aboutTabs" role="tablist">
            <button type="button" data-tab="profil" role="tab" class="about-tab px-4 py-3 border-b-2">👤 Profil</button>
            <button type="button" data-tab="layanan" role="tab" class="about-tab px-4 py-3 border-b-2">💡 Layanan Digital</button>
            <button type="button" data-tab="prestasi" role="tab" class="about-tab px-4 py-3 border-b-2">🏆 Prestasi</button>
            <button type="button" data-tab="keanggotaan" role="tab" class="about-tab px-4 py-3 border-b-2">🪪 Keanggotaan</button>
        </div>
    </div>

    <div class="bg-white rounded-b-xl border p-5 sm:p-8 text-sm leading-relaxed text-stone-700">

        {{-- ============ PROFIL ============ --}}
        <section id="tab-profil" class="about-panel space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-20 w-20 object-contain">
                <div>
                    <h2 class="text-lg font-bold text-stone-800">UPA. Perpustakaan Politeknik Negeri Lhokseumawe</h2>
                    <p class="italic text-yellow-700">&ldquo;Library is the gate to knowledge&rdquo;</p>
                </div>
            </div>

            <p>
                Perpustakaan Politeknik Negeri Lhokseumawe (PNL) merupakan pusat layanan informasi yang mendukung
                pelaksanaan Tri Dharma Perguruan Tinggi melalui penyediaan koleksi cetak, digital, dan berbagai sumber
                informasi ilmiah. Dengan memanfaatkan Sistem Otomasi Perpustakaan (SLiMS), kami berkomitmen memberikan
                layanan yang cepat, mudah, dan berkualitas bagi seluruh sivitas akademika.
            </p>
            <p>
                Perpustakaan PNL terus berinovasi untuk mendukung lingkungan belajar yang modern, nyaman, dan mendukung
                kegiatan pendidikan, penelitian, serta pengabdian kepada masyarakat.
            </p>

            <div>
                <h3 class="font-bold text-stone-800 mb-2">Visi</h3>
                <p class="bg-yellow-50 border-l-4 border-yellow-500 px-4 py-3 rounded-r-lg">
                    Menjadi Unit Layanan Pustaka yang Berbasis Teknologi Informasi.
                </p>
            </div>

            <div>
                <h3 class="font-bold text-stone-800 mb-2">Misi</h3>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Memberi pelayanan sesuai dengan kebutuhan pemustaka yang berbasis teknologi informasi.</li>
                    <li>Mengembangkan data repository yang open access.</li>
                    <li>Mengembangkan total quality manajemen dalam pengelolaan perpustakaan yang terakreditasi.</li>
                </ol>
            </div>

            <div>
                <h3 class="font-bold text-stone-800 mb-2">Koleksi</h3>
                <p>
                    Koleksi mencakup bahan fiksi dan ilmu pengetahuan dalam bentuk cetak maupun digital (CD-ROM, CD, VCD,
                    dan DVD). Perpustakaan juga menyediakan terbitan berkala berupa surat kabar (harian) dan majalah (bulanan).
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <h3 class="font-bold text-stone-800">Informasi & Kontak</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs text-stone-400 uppercase">Alamat</dt>
                            <dd>Jl. Banda Aceh–Medan Km. 280,3, Buketrata, Mesjid Punteut, Blang Mangat, Kota Lhokseumawe, Aceh 24301</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-400 uppercase">Telepon</dt>
                            <dd>(0645) 42670</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-400 uppercase">Jam Operasional</dt>
                            <dd>Senin&ndash;Jumat, 08.00&ndash;17.30 WIB</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-400 uppercase">Media Sosial</dt>
                            <dd class="flex flex-wrap gap-x-4 gap-y-1">
                                <a class="text-yellow-700 hover:underline" target="_blank" rel="noopener" href="https://www.facebook.com/perpustakaan.pnl">Facebook</a>
                                <a class="text-yellow-700 hover:underline" target="_blank" rel="noopener" href="https://www.instagram.com/perpustakaan_pnl_inst/">Instagram</a>
                                <a class="text-yellow-700 hover:underline" target="_blank" rel="noopener" href="https://www.youtube.com/@LibraryPNL">YouTube</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stone-400 uppercase">Website Resmi</dt>
                            <dd class="flex flex-wrap gap-x-4 gap-y-1">
                                <a class="text-yellow-700 hover:underline" target="_blank" rel="noopener" href="https://library.pnl.ac.id">library.pnl.ac.id</a>
                                <a class="text-yellow-700 hover:underline" target="_blank" rel="noopener" href="https://pustaka.pnl.ac.id">pustaka.pnl.ac.id</a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl overflow-hidden border min-h-[240px]">
                    <iframe
                        title="Lokasi Perpustakaan PNL"
                        class="w-full h-full min-h-[240px]"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4931.152548257125!2d97.15735070000001!3d5.120074600000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304783c1e093324d%3A0x8ba21a9ecc211d67!2sPerpustakaan%20PNL!5e1!3m2!1sen!2sid!4v1785312422117!5m2!1sen!2sid">
                    </iframe>
                </div>
            </div>
        </section>

        {{-- ============ LAYANAN DIGITAL ============ --}}
        <section id="tab-layanan" class="about-panel hidden space-y-6">
            <p>
                Perpustakaan PNL menyediakan berbagai layanan dan sumber informasi digital yang dapat diakses
                sivitas akademika untuk menunjang pembelajaran dan penelitian.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ([
                    ['OPAC (Katalog Online)', 'Pencarian koleksi perpustakaan PNL berbasis SLiMS.', 'https://library.pnl.ac.id/'],
                    ['Rama Repository', 'Repositori karya ilmiah PNL yang bersifat open access.', 'https://pustaka.pnl.ac.id/'],
                    ['E-Jurnal PNL', 'Portal jurnal ilmiah Politeknik Negeri Lhokseumawe.', 'https://e-jurnal.pnl.ac.id/'],
                    ['Turnitin', 'Pengecekan similarity karya tulis ilmiah.', 'https://pnl.turnitin.com/'],
                    ['Typeset / SciSpace', 'Bantuan membaca dan menulis artikel ilmiah.', 'https://typeset.io/'],
                    ['Khastara (Perpusnas)', 'Layanan koleksi khazanah pustaka Nusantara dari Perpustakaan Nasional.', 'https://khastara.perpusnas.go.id/'],
                    ['E-Perpus Unimal', 'Perpustakaan digital melalui Kubuku.', 'https://kubuku.id/download/e-perpus-unimal/'],
                    ['Pesan Inden Koleksi', 'Pemesanan inden koleksi bahan pustaka.', 'https://forms.gle/7X9eWS5pfAb4VvmU9'],
                ] as [$judul, $ket, $url])
                    <a href="{{ $url }}" target="_blank" rel="noopener"
                       class="block border rounded-xl p-4 hover:border-yellow-400 hover:shadow-sm transition">
                        <p class="font-semibold text-stone-800">{{ $judul }} <span class="text-yellow-700">↗</span></p>
                        <p class="text-xs text-stone-500 mt-1">{{ $ket }}</p>
                    </a>
                @endforeach
            </div>

            <div>
                <h3 class="font-bold text-stone-800 mb-2">Database Elektronik</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach (['Semantic Scholar', 'ScienceDirect', 'ProQuest', 'PQDT', 'PDFDrive', 'MDPI', 'IEEE', 'EBSCO'] as $db)
                        <span class="text-xs bg-yellow-50 border border-yellow-100 text-yellow-700 px-3 py-1 rounded-full">{{ $db }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============ PRESTASI ============ --}}
        <section id="tab-prestasi" class="about-panel hidden space-y-6">
            <div class="flex items-start gap-4 border rounded-xl p-4">
                <div class="text-3xl">🏆</div>
                <div>
                    <p class="font-semibold text-stone-800">Akreditasi A Nasional</p>
                    <p class="text-stone-600 mt-1">
                        Perpustakaan Politeknik Negeri Lhokseumawe meraih Akreditasi A Nasional.
                    </p>
                    <a class="text-xs text-yellow-700 hover:underline mt-2 inline-block" target="_blank" rel="noopener"
                       href="https://pustaka.pnl.ac.id/berita/Selamat-Perpustakaan-Politeknik-Negeri-Lhokseumawe-Raih-Akreditasi-A-Nasional">
                        Baca beritanya →
                    </a>
                </div>
            </div>

            <div class="flex items-start gap-4 border rounded-xl p-4">
                <div class="text-3xl">📄</div>
                <div>
                    <p class="font-semibold text-stone-800">Laporan Kinerja</p>
                    <p class="text-stone-600 mt-1">Library Annual Report UPA Perpustakaan Politeknik Negeri Lhokseumawe Tahun 2025.</p>
                    <a class="text-xs text-yellow-700 hover:underline mt-2 inline-block" target="_blank" rel="noopener"
                       href="https://pustaka.pnl.ac.id/Home/download_laporan/LIBRARY_ANNUAL_REPORT_2025_fnl.pdf">
                        Unduh laporan (PDF) →
                    </a>
                </div>
            </div>
        </section>

        {{-- ============ KEANGGOTAAN ============ --}}
        <section id="tab-keanggotaan" class="about-panel hidden space-y-4">
            <p>
                Untuk meminjam koleksi, pengunjung harus terlebih dahulu menjadi anggota perpustakaan dan mematuhi
                syarat dan ketentuan yang berlaku. Pengunjung umum tetap dapat melihat katalog, tugas akhir, event, dan
                artikel tanpa login.
            </p>
            <ul class="list-disc list-inside space-y-1">
                <li>Mahasiswa dan dosen dapat mendaftar mandiri lewat halaman Daftar.</li>
                <li>Anggota dapat mengajukan peminjaman, reservasi buku, mendaftar event, dan bertanya kepada pustakawan.</li>
            </ul>
            <div class="flex gap-3 pt-2">
                @guest
                    <a href="{{ route('register') }}" class="bg-stone-800 hover:bg-stone-900 text-white text-sm font-semibold px-5 py-2 rounded-full">Daftar Anggota</a>
                    <a href="{{ route('login') }}" class="border border-yellow-500 text-yellow-700 hover:bg-yellow-50 text-sm font-semibold px-5 py-2 rounded-full">Masuk</a>
                @else
                    <span class="text-xs text-stone-500">Kamu sudah masuk sebagai anggota.</span>
                @endguest
            </div>
        </section>
    </div>

    {{-- ============ PROFIL PUSTAKAWAN ============ --}}
    <div class="mt-8">
        <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
            <span class="w-1.5 h-5 bg-yellow-500 inline-block rounded"></span>
            Profil Pustakawan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($pustakawan as $p)
                <div class="bg-white border rounded-xl p-4 flex gap-4">
                    <div class="w-24 h-28 shrink-0 rounded-lg overflow-hidden bg-stone-100 flex items-center justify-center">
                        @if ($p['foto'])
                            <img src="{{ $p['foto'] }}" alt="Foto {{ $p['nama'] }}" loading="lazy"
                                 class="w-full h-full object-cover"
                                 onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-4xl text-stone-400',textContent:'👤'}))">
                        @else
                            <span class="text-4xl text-stone-400">👤</span>
                        @endif
                    </div>
                    <dl class="text-sm grid grid-cols-[auto_1fr] gap-x-3 gap-y-1 content-start">
                        <dt class="font-semibold text-stone-700">Nama</dt>
                        <dd class="text-stone-700">{{ $p['nama'] }}</dd>
                        <dt class="font-semibold text-stone-700">Jabatan</dt>
                        <dd class="text-stone-600">{{ $p['jabatan'] }}</dd>
                        <dt class="font-semibold text-stone-700">Surel</dt>
                        <dd class="text-stone-600 break-all">{{ $p['surel'] ?? '' }}</dd>
                        <dt class="font-semibold text-stone-700">Media Sosial</dt>
                        <dd class="text-stone-600">{{ $p['sosmed'] ?? '' }}</dd>
                    </dl>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-stone-400 mt-3">Sumber: library.pnl.ac.id &mdash; halaman Pustakawan.</p>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        var tabs = document.querySelectorAll('.about-tab');
        var panels = document.querySelectorAll('.about-panel');

        function show(name) {
            if (!document.getElementById('tab-' + name)) name = 'profil';
            panels.forEach(function (p) { p.classList.toggle('hidden', p.id !== 'tab-' + name); });
            tabs.forEach(function (t) {
                var on = t.dataset.tab === name;
                t.classList.toggle('border-yellow-500', on);
                t.classList.toggle('text-yellow-700', on);
                t.classList.toggle('border-transparent', !on);
                t.classList.toggle('text-stone-500', !on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
            });
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () {
                show(t.dataset.tab);
                history.replaceState(null, '', '#' + t.dataset.tab);
            });
        });

        show(location.hash.replace('#', '') || 'profil');
    })();
</script>
@endpush
