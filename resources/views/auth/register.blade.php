<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan PNL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: {50:'#fdf8e8',100:'#faf0c8',400:'#f2c94c',500:'#e8b923',600:'#c99a12',700:'#9c780e'} } } } }
    </script>
</head>
<body class="bg-[#efece2] min-h-screen flex items-center justify-center p-4 py-10">

    <div class="w-full max-w-md">

        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo-pnl.png') }}" alt="Logo PNL" class="h-16 w-16 object-contain mb-2">
            <h1 class="font-bold text-gray-800 text-lg">Perpustakaan PNL</h1>
            <p class="text-sm text-gray-500">Daftar sebagai anggota (Mahasiswa/Dosen)</p>
        </div>

        <div class="bg-white rounded-2xl border p-6 sm:p-8">

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Pilih jenis anggota dulu, biar label NIM/NIP menyesuaikan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Daftar sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($roles as $role)
                            <label class="flex items-center justify-center gap-2 border rounded-lg py-2.5 text-sm cursor-pointer has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50">
                                <input type="radio" name="jenis_user_id" value="{{ $role->id }}"
                                       @checked(old('jenis_user_id', $roles->first()?->id) == $role->id)
                                       class="text-gold-600 focus:ring-gold-400">
                                <span class="capitalize">{{ $role->nama_role }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">NIM / NIP</label>
                    <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-400">
                </div>

                <button type="submit"
                        class="w-full bg-gold-600 hover:bg-gold-700 text-white font-semibold text-sm py-2.5 rounded-lg">
                    Daftar
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-gold-700 font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            <a href="{{ route('home') }}" class="hover:underline">&larr; Kembali ke beranda</a>
        </p>
    </div>

</body>
</html>
