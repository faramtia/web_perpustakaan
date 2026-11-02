<x-nav-link route-name="petugas.dashboard" label="Dashboard" icon="🏠" />
<x-nav-link route-name="petugas.peminjaman.index" label="Verifikasi Peminjaman" icon="✅" />
<x-nav-link route-name="katalog.index" label="Stok Buku" icon="📖" />
<x-nav-link route-name="petugas.reservasi.index" label="Reservasi" icon="⏳" />
<x-nav-link route-name="petugas.absensi.index" label="Input Absensi" icon="🧾" />
<a href="{{ route('kiosk.absensi') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-gray-300 hover:bg-gray-800">
    <span class="w-5 text-center">📷</span>
    <span>Kiosk Absensi</span>
</a>
<x-nav-link route-name="epublication.index" label="E-Publication" icon="📚" />
<x-nav-link route-name="petugas.feedback.index" label="Balas Feedback" icon="💬" />
<x-nav-link route-name="petugas.event.index" label="Event" icon="📅" />
