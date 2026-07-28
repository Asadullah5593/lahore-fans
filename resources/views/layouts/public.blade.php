<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Where to Buy | Lahore Fan')</title>
    <meta name="description" content="@yield('description', 'Find authorised Lahore Fan dealers and outlets across Pakistan.')">
    @hasSection('keywords')<meta name="keywords" content="@yield('keywords')">@endif
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'Where to Buy | Lahore Fan')">
    <meta property="og:description" content="@yield('og_description', 'Find authorised Lahore Fan dealers and outlets across Pakistan.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('twitter_title', 'Where to Buy | Lahore Fan')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Find authorised Lahore Fan dealers and outlets across Pakistan.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.png'))">

    <link rel="icon" href="{{ asset('images/logo.png') }}">

    {{-- Fonts: Manrope (matches lahorefan.com) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="flex min-h-screen flex-col">
    @include('partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
