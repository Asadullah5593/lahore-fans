<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sign in · {{ config('app.name', 'Lahore Fan Outlets') }}</title>

        <link rel="icon" href="{{ asset('images/logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-gray-100 px-4 py-10">
            <a href="{{ route('where-to-buy') }}">
                <x-application-logo class="w-40" />
            </a>
            <p class="mt-3 text-sm font-semibold uppercase tracking-wide text-brand">Outlets Admin</p>

            <div class="mt-6 w-full overflow-hidden rounded-lg border border-line bg-white px-6 py-6 shadow-sm sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
