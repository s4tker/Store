<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ElectroShop')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="min-h-screen antialiased bg-slate-50 flex flex-col">

    @include('partials.navbar')

    <main class="w-full max-w-7xl mx-auto flex-1 p-4 md:p-10">
        @yield('content')
    </main>

    @include('partials.cart-drawer')
    @include('partials.footer')

    @yield('scripts')
</body>
</html>
