<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <title>
        @yield('title')
    </title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    @yield('js')
    <script>
        const savedTheme = localStorage.getItem('color-theme');
        const resolvedTheme = savedTheme === 'light' || savedTheme === 'dark'
            ? savedTheme
            : 'dark';
        const savedSidebarState = localStorage.getItem('admin-sidebar');
        const resolvedSidebarState = savedSidebarState === 'collapsed' ? 'collapsed' : 'expanded';
        document.documentElement.classList.toggle('dark', resolvedTheme === 'dark');
        document.documentElement.dataset.theme = resolvedTheme;
        document.documentElement.dataset.sidebarState = resolvedSidebarState;
    </script>
</head>

<body class="min-h-full font-sans antialiased">
    @yield('content')
    @livewireScripts
</body>

@yield('scripts')
</html>
