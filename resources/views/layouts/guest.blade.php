<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title', 'Open Library PNL')</title>
</head>
<body class="flex min-h-screen flex-col bg-stone-100 text-stone-800 antialiased">

    @include('public.partials.navbar')

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
        <x-flash />
        @yield('content')
    </main>

    @include('public.partials.footer')

    @stack('scripts')
</body>
</html>
