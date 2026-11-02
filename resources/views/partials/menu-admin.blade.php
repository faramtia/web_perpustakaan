<x-nav-link route-name="admin.dashboard" label="Dashboard" icon="🏠" />
<x-nav-link route-name="katalog.index" label="Kelola Buku" icon="📖" />
<x-nav-link route-name="admin.kategori.index" label="Kategori" icon="🏷️" />
<x-nav-link route-name="admin.lokasi.index" label="Lokasi Rak" icon="📍" />
<x-nav-link route-name="admin.tipe-koleksi.index" label="Tipe Koleksi" icon="🗂️" />
<x-nav-link route-name="petugas.peminjaman.index" label="Peminjaman" icon="🔄" />
<x-nav-link route-name="petugas.reservasi.index" label="Reservasi" icon="⏳" />
<x-nav-link route-name="petugas.absensi.index" label="Absensi" icon="🧾" />
<a href="{{ route('kiosk.absensi') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-gray-300 hover:bg-gray-800">
    <span class="w-5 text-center">📷</span>
    <span>Kiosk Absensi</span>
</a>
<x-nav-link route-name="epublication.index" label="E-Publication" icon="📚" />
<x-nav-link route-name="petugas.feedback.index" label="Feedback" icon="💬" />
<x-nav-link route-name="event.index" label="Event" icon="📅" />
<x-nav-link route-name="artikel.index" label="Artikel" icon="📝" />
<x-nav-link route-name="admin.laporan.index" label="Laporan" icon="📊" />
