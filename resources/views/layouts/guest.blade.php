<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Campus Parking') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122polished">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="guest-auth-layout">
                <aside class="guest-auth-brand">
                    <a href="/" class="guest-brand-mark" aria-label="Campus Parking home">
                        <x-application-logo />
                        <span class="guest-brand-title">Campus Parking</span>
                    </a>

                    <div class="guest-brand-copy">
                        <h1>Park smarter.<br>Move easier.</h1>
                        <p>Find available campus spaces, reserve slots, and manage vehicle access from one secure portal.</p>
                    </div>
                </aside>

                <main class="guest-auth-card">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
