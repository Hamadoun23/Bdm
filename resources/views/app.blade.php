<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FF6A3A">
    <title inertia>{{ config('app.name', 'Campagne BDM') }}</title>

    @include('layouts.partials.pwa')
    @include('layouts.partials.favicon')

    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
</head>
<body class="font-sans antialiased text-gray-900">
    @inertia
    @include('layouts.partials.register-sw')
</body>
</html>
