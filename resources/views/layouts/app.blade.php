<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    @stack('head')
</head>

<body class="bg-gray-100 text-gray-800">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')


    {{-- AREA UTAMA --}}
    <div class="ml-64">

        {{-- NAVBAR --}}
        @include('layouts.navbar')


        <main class="p-6">

            @yield('content')

        </main>

    </div>

    @stack('scripts')
</body>

</html>