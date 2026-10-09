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

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full border rounded-lg pl-4 pr-16 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400 @error('password') border-red-400 @enderror">
                        <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 px-3 text-xs font-medium text-gray-500 hover:text-gold-700">
                            Lihat
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-500">
                    <input type="checkbox" name="remember" class="rounded border-gray-300" @checked(old('remember'))>
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
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            this.textContent = tampil ? 'Sembunyikan' : 'Lihat';
        });
    </script>
</body>
</html>
