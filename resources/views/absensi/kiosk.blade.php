<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Pengunjung - Perpustakaan PNL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: {50:'#fdf8e8',100:'#faf0c8',400:'#f2c94c',500:'#e8b923',600:'#c99a12',700:'#9c780e'} } } } }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gold-700 min-h-screen flex items-center justify-center p-4 text-white">

    <div class="w-full max-w-xl text-center">
        <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-20 w-20 object-contain mx-auto mb-4 bg-white rounded-full p-1.5">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gold-400">Absensi Pengunjung</h1>
        <p class="text-gray-200 mt-1">Perpustakaan Politeknik Negeri Lhokseumawe</p>
        <p id="jam" class="text-sm text-gray-300 mt-1"></p>

        <form id="formAbsen" class="mt-8" autocomplete="off">
            <label for="nim" class="block text-sm text-gray-200 mb-2">Masukkan NIM/NIP kamu, lalu tekan Enter (bisa juga scan barcode kartu)</label>
            <input id="nim" type="text" inputmode="numeric" autofocus autocomplete="off"
                   class="w-full text-center text-2xl tracking-widest rounded-xl px-4 py-4 text-gray-800 focus:outline-none focus:ring-4 focus:ring-gold-400"
                   placeholder="NIM / NIP">
        </form>

        <div id="hasil" class="hidden mt-6 rounded-xl px-5 py-4 text-lg font-semibold"></div>
    </div>

<script>
    var input = document.getElementById('nim');
    var hasil = document.getElementById('hasil');
    var token = document.querySelector('meta[name="csrf-token"]').content;
    var sibuk = false, timer = null;

    function tampil(kelas, teks) {
        hasil.className = 'mt-6 rounded-xl px-5 py-4 text-lg font-semibold ' + kelas;
        hasil.textContent = teks;
        clearTimeout(timer);
        timer = setTimeout(function () { hasil.className = 'hidden'; }, 4000);
    }

    document.getElementById('formAbsen').addEventListener('submit', function (e) {
        e.preventDefault();
        var nim = input.value.trim();
        if (!nim || sibuk) return;
        sibuk = true;

        fetch('{{ route('kiosk.absensi.store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ nim_nip: nim })
        })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, status: r.status, data: d }; }); })
        .then(function (res) {
            var d = res.data;
            if (res.status === 403 || res.status === 419) {
                tampil('bg-red-500', 'Halaman kedaluwarsa. Muat ulang halaman (F5).');
            } else if (res.status === 429) {
                tampil('bg-red-500', 'Terlalu banyak percobaan. Tunggu sebentar.');
            } else if (d.status === 'ok') {
                tampil('bg-emerald-600', 'Selamat datang, ' + d.nama + '! Absensi tercatat.');
            } else if (d.status === 'sudah') {
                tampil('bg-blue-600', d.nama + ': sudah tercatat hadir hari ini.');
            } else {
                tampil('bg-red-500', d.pesan || 'Gagal mencatat absensi.');
            }
        })
        .catch(function () { tampil('bg-red-500', 'Tidak dapat terhubung ke server.'); })
        .finally(function () { sibuk = false; input.value = ''; input.focus(); });
    });

    // Fokus selalu kembali ke kolom input supaya pemindai langsung bisa dipakai.
    document.addEventListener('click', function () { input.focus(); });
    input.addEventListener('blur', function () { setTimeout(function () { input.focus(); }, 50); });

    function jam() {
        document.getElementById('jam').textContent =
            new Date().toLocaleString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }
    jam(); setInterval(jam, 30000);
</script>
</body>
</html>
