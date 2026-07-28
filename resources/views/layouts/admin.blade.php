<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Lahore Fan Outlets</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 text-ink">
    {{-- Top navigation --}}
    <nav x-data="{ open: false }" class="bg-brand text-white">
        <div class="mx-auto flex max-w-container items-center justify-between px-4 py-3">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.stores.index') }}" class="flex items-center gap-2 font-bold text-white">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-8 w-auto rounded bg-white/90 p-1">
                    <span class="hidden sm:inline">Outlets Admin</span>
                </a>
                <div class="hidden items-center gap-1 sm:flex">
                    @php $section = request()->segment(2); @endphp
                    <a href="{{ route('admin.stores.index') }}"
                       class="rounded px-3 py-1.5 text-sm font-semibold transition {{ $section === 'stores' ? 'bg-white/20' : 'hover:bg-white/10' }}">Outlets</a>
                    <a href="{{ route('admin.cities.index') }}"
                       class="rounded px-3 py-1.5 text-sm font-semibold transition {{ $section === 'cities' ? 'bg-white/20' : 'hover:bg-white/10' }}">Cities</a>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded px-3 py-1.5 text-sm font-semibold transition {{ $section === 'users' ? 'bg-white/20' : 'hover:bg-white/10' }}">Users</a>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('where-to-buy') }}" target="_blank" rel="noopener"
                   class="hidden text-white/90 hover:text-white sm:inline">View site &nearr;</a>
                <span class="hidden text-white/80 md:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded bg-white/15 px-3 py-1.5 font-semibold text-white transition hover:bg-white/25">
                        Log out
                    </button>
                </form>
            </div>
        </div>
        {{-- Mobile section links --}}
        <div class="flex gap-1 border-t border-white/15 px-4 py-2 sm:hidden">
            <a href="{{ route('admin.stores.index') }}" class="rounded px-3 py-1.5 text-sm font-semibold {{ request()->segment(2) === 'stores' ? 'bg-white/20' : '' }}">Outlets</a>
            <a href="{{ route('admin.cities.index') }}" class="rounded px-3 py-1.5 text-sm font-semibold {{ request()->segment(2) === 'cities' ? 'bg-white/20' : '' }}">Cities</a>
            <a href="{{ route('admin.users.index') }}" class="rounded px-3 py-1.5 text-sm font-semibold {{ request()->segment(2) === 'users' ? 'bg-white/20' : '' }}">Users</a>
        </div>
    </nav>

    <main class="mx-auto max-w-container px-4 py-8">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
