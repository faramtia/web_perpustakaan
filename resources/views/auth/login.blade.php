<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Perpustakaan PNL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: {50:'#fdf8e8',100:'#faf0c8',400:'#f2c94c',500:'#e8b923',600:'#c99a12',700:'#9c780e'} } } } }
    </script>
</head>
<body class="bg-[#efece2] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-16 w-16 object-contain mb-2">
            <h1 class="font-bold text-gray-800 text-lg">Perpustakaan PNL</h1>
            <p class="text-sm text-gray-500">Masuk ke akun kamu</p>
        </div>

        <div class="bg-white rounded-2xl border p-6 sm:p-8">

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-500">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-gold-600 hover:bg-gold-700 text-white font-semibold text-sm py-2.5 rounded-lg">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-gold-700 font-semibold hover:underline">Daftar sebagai Mahasiswa/Dosen</a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="{{ route('home') }}" class="hover:underline">&larr; Kembali ke beranda</a>
        </p>

        {{-- Bantuan buat testing: akun contoh dari seeder --}}
        <div class="mt-6 bg-white/60 border border-dashed rounded-xl p-4 text-xs text-gray-500">
            <p class="font-semibold mb-1">Akun contoh (dari seeder, password: <code>password</code>):</p>
            <ul class="space-y-0.5">
                <li>admin@pnl.ac.id</li>
                <li>petugas@pnl.ac.id</li>
                <li>mahasiswa@pnl.ac.id</li>
                <li>dosen@pnl.ac.id</li>
            </ul>
        </div>
    </div>

</body>
</html>
