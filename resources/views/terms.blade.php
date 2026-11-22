<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms &amp; Conditions — {{ config('app.name', 'Campus Parking System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    @php
        $backUrl = url()->previous() !== url()->current() ? url()->previous() : url('/');
        $registerUrl = \Illuminate\Support\Facades\Route::has('register') ? route('register') : url('/register');
    @endphp

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-20 border-b border-blue-900/40 bg-gradient-to-r from-blue-950 via-blue-800 to-blue-700 text-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 text-xs font-black tracking-tight text-white ring-1 ring-white/25">
                    CP
                </span>
                <div class="leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-white sm:text-base">Campus Parking</span>
                    <span class="block text-[11px] font-medium text-blue-100/85">Policy &amp; Guidelines</span>
                </div>
            </a>

            <a
                href="{{ $backUrl }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3.5 py-1.5 text-xs font-bold text-white ring-1 ring-white/25 transition hover:bg-white/25"
            >
                <span aria-hidden="true">&larr;</span>
                <span>Back</span>
            </a>
        </div>
    </header>

    <!-- Main Policy Document Container -->
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <article class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">

            <!-- Document Header Banner -->
            <div class="border-b border-slate-200 bg-slate-50/80 px-6 py-6 sm:px-10 sm:py-8">
                <p class="text-xs font-extrabold uppercase tracking-wider text-blue-700">
                    Campus Parking System
                </p>
                <h1 class="mt-1.5 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">
                    Terms &amp; Conditions
                </h1>
                <p class="mt-2 text-xs font-semibold text-slate-500 sm:text-sm">
                    Effective November 21, 2026
                </p>
            </div>

            <!-- Policy Sections -->
            <div class="divide-y divide-slate-100 px-6 py-4 sm:px-10 sm:py-6">
                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        1. Use of the System
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        The Campus Parking System is provided for authorized campus vehicle parking, slot reservation, gate logging, and administration. Users must provide accurate account and vehicle information.
                    </p>
                </section>

                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        2. Driver Responsibilities
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Drivers must follow campus traffic rules, occupy only assigned or reserved spaces, and release reserved slots when no longer needed. Misuse may result in account or parking access restrictions.
                    </p>
                </section>

                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        3. Guard Responsibilities
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Authorized guards must accurately record vehicle entry and exit details and handle parking logs responsibly. Records should not be altered or shared outside official system use.
                    </p>
                </section>

                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        4. Account Security
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Users are responsible for protecting login credentials. Sharing accounts or intentionally attempting unauthorized access is prohibited.
                    </p>
                </section>

                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        5. Data and Privacy
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        The system may process names, email addresses, vehicle details, parking activity, and time records for campus parking administration and safety purposes.
                    </p>
                </section>

                <section class="py-5">
                    <h2 class="text-base font-extrabold text-slate-900 sm:text-lg">
                        6. Fair Use
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Users must not disrupt availability checks, reservation processes, records, or security controls. Administrators may update rules and suspend misuse.
                    </p>
                </section>
            </div>

            <!-- Action Footer -->
            <div class="flex flex-col items-start justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:items-center sm:px-10">
                <p class="text-xs text-slate-500">
                    By continuing to use the portal, you agree to abide by these campus parking guidelines.
                </p>

                <a
                    href="{{ $registerUrl }}"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-blue-600 px-5 text-xs font-extrabold tracking-wide text-white shadow-xs transition hover:bg-blue-700"
                >
                    Continue to Registration
                </a>
            </div>
        </article>
    </main>
</body>
</html>