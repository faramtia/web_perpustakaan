<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan PNL')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50:  '#fdf8e8',
                            100: '#faf0c8',
                            400: '#f2c94c',
                            500: '#e8b923',
                            600: '#c99a12',
                            700: '#9c780e',
                        },
                    },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="flex min-h-screen">

        @auth
            @include('partials.sidebar')
        @endauth

        <div class="flex-1 flex flex-col min-w-0">

            @include('partials.navbar')

            <main class="flex-1 p-4 sm:p-6">

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </main>

            <footer class="text-center text-xs text-gray-400 py-4 border-t bg-white">
                &copy; {{ date('Y') }} Perpustakaan Politeknik Negeri Lhokseumawe
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
