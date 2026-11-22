<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Campus Parking') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122topfix">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased campus-app role-{{ Auth::check() ? Auth::user()->role : 'guest' }}">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        @if(session('success') || session('error'))
            <div class="smart-toast {{ session('error') ? 'smart-toast-error' : 'smart-toast-success' }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 4200)" x-show="show" x-transition>
                <span>{{ session('error') ? '!' : '✓' }}</span>
                <p>{{ session('success') ?? session('error') }}</p>
            </div>
        @endif
    </body>
</html>