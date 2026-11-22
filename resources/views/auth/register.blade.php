<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — {{ config('app.name', 'Campus Parking System') }}</title>

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
<body class="relative min-h-screen overflow-x-hidden bg-slate-950 text-slate-900 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Full-Page Campus Parking Scene Background -->
    <div class="pointer-events-none fixed inset-0 z-0">
        <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="regSkyGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#2563eb" />
                    <stop offset="55%" stop-color="#93c5fd" />
                    <stop offset="100%" stop-color="#e0f2fe" />
                </linearGradient>
                <linearGradient id="regAsphaltGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#475569" />
                    <stop offset="45%" stop-color="#334155" />
                    <stop offset="100%" stop-color="#0f172a" />
                </linearGradient>
                <linearGradient id="regBldgGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#f8fafc" />
                    <stop offset="100%" stop-color="#cbd5e1" />
                </linearGradient>
            </defs>

            <!-- Sky -->
            <rect width="1600" height="440" fill="url(#regSkyGrad)" />
            <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
            <ellipse cx="475" cy="108" rx="68" ry="22" fill="#ffffff" fill-opacity="0.78" />
            <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />

            <!-- Campus Building -->
            <rect x="640" y="150" width="960" height="245" fill="url(#regBldgGrad)" />
            <rect x="640" y="142" width="960" height="12" fill="#94a3b8" />
            <g fill="#1e3a8a" fill-opacity="0.72">
                <rect x="680" y="180" width="44" height="34" rx="3" />
                <rect x="742" y="180" width="44" height="34" rx="3" />
                <rect x="804" y="180" width="44" height="34" rx="3" />
                <rect x="866" y="180" width="44" height="34" rx="3" />
                <rect x="928" y="180" width="44" height="34" rx="3" />
                <rect x="990" y="180" width="44" height="34" rx="3" />
                <rect x="1052" y="180" width="44" height="34" rx="3" />
                <rect x="1114" y="180" width="44" height="34" rx="3" />
                <rect x="1176" y="180" width="44" height="34" rx="3" />
                <rect x="1238" y="180" width="44" height="34" rx="3" />
                <rect x="1300" y="180" width="44" height="34" rx="3" />
                <rect x="1362" y="180" width="44" height="34" rx="3" />
                <rect x="1424" y="180" width="44" height="34" rx="3" />
            </g>
            <g fill="#1e3a8a" fill-opacity="0.65">
                <rect x="680" y="234" width="44" height="34" rx="3" />
                <rect x="742" y="234" width="44" height="34" rx="3" />
                <rect x="804" y="234" width="44" height="34" rx="3" />
                <rect x="866" y="234" width="44" height="34" rx="3" />
                <rect x="928" y="234" width="44" height="34" rx="3" />
                <rect x="990" y="234" width="44" height="34" rx="3" />
                <rect x="1052" y="234" width="44" height="34" rx="3" />
                <rect x="1114" y="234" width="44" height="34" rx="3" />
                <rect x="1176" y="234" width="44" height="34" rx="3" />
                <rect x="1238" y="234" width="44" height="34" rx="3" />
                <rect x="1300" y="234" width="44" height="34" rx="3" />
                <rect x="1362" y="234" width="44" height="34" rx="3" />
                <rect x="1424" y="234" width="44" height="34" rx="3" />
            </g>

            <!-- Trees -->
            <g>
                <rect x="145" y="290" width="14" height="90" fill="#78350f" />
                <circle cx="152" cy="260" r="58" fill="#15803d" />
                <circle cx="122" cy="276" r="42" fill="#16a34a" />
                <circle cx="184" cy="276" r="44" fill="#166534" />
                <rect x="335" y="305" width="12" height="75" fill="#78350f" />
                <circle cx="341" cy="280" r="48" fill="#16a34a" />
                <rect x="550" y="300" width="14" height="80" fill="#78350f" />
                <circle cx="557" cy="272" r="52" fill="#15803d" />
                <circle cx="760" cy="345" r="46" fill="#16a34a" />
                <circle cx="1360" cy="342" r="50" fill="#15803d" />
            </g>

            <!-- Parked Cars -->
            <g opacity="0.9">
                <rect x="655" y="395" width="76" height="36" rx="8" fill="#475569" />
                <rect x="752" y="396" width="78" height="35" rx="8" fill="#1e293b" />
                <rect x="850" y="395" width="76" height="36" rx="8" fill="#cbd5e1" />
                <rect x="945" y="396" width="80" height="35" rx="8" fill="#64748b" />
            </g>

            <!-- Asphalt Lot & Curb -->
            <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#regAsphaltGrad)" />
            <polygon points="435,515 780,515 755,565 410,565" fill="#166534" />
            <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
            <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />

            <!-- Barrier & Guardhouse -->
            <rect x="742" y="510" width="34" height="86" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
            <rect x="1142" y="535" width="38" height="92" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
            <polygon points="774,548 1144,572 1144,585 774,561" fill="#ffffff" stroke="#0f172a" stroke-width="1.5" />
            <g fill="#dc2626">
                <polygon points="810,550 845,552 845,565 810,563" />
                <polygon points="880,555 915,557 915,570 880,568" />
                <polygon points="950,559 985,561 985,574 950,572" />
                <polygon points="1020,564 1055,566 1055,579 1020,577" />
                <polygon points="1090,568 1125,570 1125,583 1090,581" />
            </g>

            <rect x="1175" y="330" width="255" height="225" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" />
            <rect x="1196" y="370" width="96" height="84" rx="4" fill="#0f172a" fill-opacity="0.72" />
            <rect x="1308" y="370" width="102" height="84" rx="4" fill="#0f172a" fill-opacity="0.72" />
            <polygon points="1145,332 1458,332 1435,272 1170,272" fill="#1d4ed8" />
            <rect x="1175" y="286" width="255" height="44" rx="4" fill="#1e40af" stroke="#ffffff" stroke-width="1.5" />
            <text x="1302" y="305" text-anchor="middle" fill="#ffffff" font-family="Inter, sans-serif" font-size="15" font-weight="900" letter-spacing="2">CAMPUS</text>
            <text x="1302" y="322" text-anchor="middle" fill="#93c5fd" font-family="Inter, sans-serif" font-size="13" font-weight="800" letter-spacing="2.5">PARKING</text>
        </svg>

        <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-[2px]"></div>
    </div>

    <!-- Top Navigation Bar (No redundant buttons) -->
    <header class="relative z-20 border-b border-blue-800/60 bg-gradient-to-r from-blue-950 via-blue-800 to-blue-700 text-white shadow-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white/80 bg-blue-900/70 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="4" y="3" width="16" height="18" rx="3" />
                        <path d="M9.5 16.5V7.5h3.2a2.3 2.3 0 0 1 0 4.6H9.5" />
                    </svg>
                </span>
                <div class="border-l border-white/30 pl-3 leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-white sm:text-base">Campus Parking System</span>
                    <span class="block text-[11px] font-medium text-blue-100/90">Park Smart. Move Better.</span>
                </div>
            </a>

            <a
                href="{{ url('/') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3.5 py-1.5 text-xs sm:text-sm font-semibold text-white ring-1 ring-white/25 transition hover:bg-white/25"
            >
                <span>&larr; Home</span>
            </a>
        </div>
    </header>

    <!-- Centered Wide Register Card Over Campus Parking Background -->
    <main class="relative z-10 flex min-h-[calc(100vh-4rem)] items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-5xl overflow-hidden rounded-3xl border border-white/25 bg-white shadow-2xl shadow-slate-950/45">
            <div class="grid grid-cols-1 lg:grid-cols-12">

                <!-- Left Blue Brand Panel -->
                <div class="flex flex-col justify-between bg-gradient-to-br from-blue-950 via-blue-800 to-blue-600 p-8 text-white lg:col-span-5 lg:p-10">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/30">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <rect x="4" y="3" width="16" height="18" rx="3" />
                                <path d="M9.5 16.5V7.5h3.2a2.3 2.3 0 0 1 0 4.6H9.5" />
                            </svg>
                        </span>
                        <div>
                            <span class="block text-base font-black tracking-tight text-white">Campus Parking</span>
                            <span class="block text-[11px] font-medium text-blue-100/85">Gate &amp; Slot Portal</span>
                        </div>
                    </div>

                    <div class="my-10">
                        <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl leading-tight">
                            Park smarter.<br>
                            Move easier.
                        </h1>
                        <p class="mt-4 max-w-sm text-sm leading-relaxed text-blue-100/90">
                            Find available campus spaces, reserve slots, and manage vehicle access from one secure portal.
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-white/15 pt-4 text-xs text-blue-100/85">
                        <span class="inline-flex items-center gap-1.5 font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Live Slot Monitoring
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200">P BAY ACCESS</span>
                    </div>
                </div>

                <!-- Right Existing Register Form (Preserving all routes & fields) -->
                <div class="flex flex-col justify-center bg-white p-8 sm:p-10 lg:col-span-7 lg:px-14 lg:py-10">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="registration-terms mt-5">
                            <label for="terms" class="inline-flex items-start gap-3">
                                <input id="terms" type="checkbox" name="terms" value="1" required class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="{{ route('terms') }}" target="_blank" class="font-semibold text-indigo-600 hover:text-indigo-800">Terms &amp; Conditions</a> and responsible use policy.
                                </span>
                            </label>
                            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>

                        <div class="mt-6">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                                </div>
                            </div>

                            <a href="{{ route('google.login') }}"
                               class="mt-6 w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-1.5 3.9-5.5 3.9-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.8 3.1 14.7 2 12 2 6.5 2 2 6.5 2 12s4.5 10 10 10c5.8 0 9.6-4.1 9.6-9.8 0-.7-.1-1.2-.2-1.7H12z"/>
                                    <path fill="#34A853" d="M3.9 7.4l3.4 2.5c.9-1.7 2.8-2.9 4.7-2.9 1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.8 3.1 14.7 2 12 2 8.1 2 4.9 4.3 3.9 7.4z"/>
                                    <path fill="#FBBC05" d="M12 22c2.7 0 5-.9 6.7-2.4l-3.1-2.6c-.8.5-1.8.9-3.6.9-3.4 0-5.2-2.2-5.5-3.9l-3.2 2.5C2.2 18.9 6.7 22 12 22z"/>
                                    <path fill="#4285F4" d="M3.9 7.4C3.4 8.6 3.1 9.9 3.1 11.2s.3 2.6.8 3.8l3.4-2.5c-.2-.6-.3-1.2-.3-1.9s.1-1.3.3-1.9L3.9 7.4z"/>
                                </svg>
                                {{ __('Continue with Google') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>