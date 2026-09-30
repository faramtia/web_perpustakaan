<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Politeknik Negeri Lhokseumawe')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {50:'#fdf8e8',100:'#faf0c8',400:'#f2c94c',500:'#e8b923',600:'#c99a12',700:'#9c780e'},
                    },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-[#efece2] text-gray-800 antialiased">

    @include('public.partials.navbar')

    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    @include('public.partials.footer')

    @stack('scripts')
</body>
</html>
