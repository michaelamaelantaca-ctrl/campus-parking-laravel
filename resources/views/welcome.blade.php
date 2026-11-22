<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Campus Parking System') }} — Park Smart. Move Better.</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&family=pacifico&display=swap" rel="stylesheet" />

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
        .font-script-welcome {
            font-family: 'Pacifico', cursive;
            text-shadow: 0 3px 14px rgba(15, 23, 42, 0.55);
        }
        .hero-text-shadow {
            text-shadow: 0 3px 16px rgba(15, 23, 42, 0.62);
        }
        .glass-feature-card {
            background: rgba(15, 23, 42, 0.52);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }
    </style>
</head>
<body class="bg-slate-950 text-white antialiased selection:bg-blue-600 selection:text-white">

    @php
        $loginUrl = \Illuminate\Support\Facades\Route::has('login') ? route('login') : url('/login');
        $registerUrl = \Illuminate\Support\Facades\Route::has('register') ? route('register') : url('/register');
        $dashboardUrl = \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/dashboard');
        $termsUrl = \Illuminate\Support\Facades\Route::has('terms') ? route('terms') : url('/terms');
        $primaryGetStartedUrl = auth()->check() ? $dashboardUrl : $registerUrl;
    @endphp

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-30 border-b border-blue-800/60 bg-gradient-to-r from-blue-950 via-blue-800 to-blue-700 shadow-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white/80 bg-blue-900/70 text-white shadow-xs">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 3l8 4v5c0 5-3.4 8.6-8 9.8-4.6-1.2-8-4.8-8-9.8V7l8-4z" />
                        <path d="M10 15V9h2.5a1.8 1.8 0 0 1 0 3.6H10" />
                    </svg>
                </span>
                <div class="border-l border-white/30 pl-3 leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-white sm:text-base">Campus Parking System</span>
                    <span class="block text-[11px] font-medium text-blue-100/90">Park Smart. Move Better.</span>
                </div>
            </a>

            <nav class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm font-semibold">
                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3 py-1.5 text-white ring-1 ring-white/25 transition hover:bg-white/25"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5z" />
                    </svg>
                    <span>Home</span>
                </a>

                @auth
                    <a
                        href="{{ $dashboardUrl }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-blue-50 transition hover:bg-white/10 hover:text-white"
                    >
                        <span>Dashboard</span>
                    </a>
                @else
                    <a
                        href="{{ $loginUrl }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-blue-50 transition hover:bg-white/10 hover:text-white"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a
                        href="{{ $registerUrl }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-blue-50 transition hover:bg-white/10 hover:text-white"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M19 8v6M22 11h-6" />
                        </svg>
                        <span>Register</span>
                    </a>
                @endauth

                <button
                    type="button"
                    onclick="window.openContactModal()"
                    class="hidden sm:inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-blue-50 transition hover:bg-white/10 hover:text-white cursor-pointer"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="2" y="4" width="20" height="16" rx="2" />
                        <path d="M22 6l-10 7L2 6" />
                    </svg>
                    <span>Contact</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Full-Bleed Campus Gate Parking Hero -->
    <section class="relative min-h-[calc(100vh-4rem)] w-full overflow-hidden flex flex-col justify-between">

        <!-- Realistic Campus Gate & Parking Lot Background Scene -->
        <div class="pointer-events-none absolute inset-0 z-0">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="asphaltGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="bldgGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>

                <!-- Sky -->
                <rect width="1600" height="440" fill="url(#skyGrad)" />
                <!-- Soft Clouds -->
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="475" cy="108" rx="68" ry="22" fill="#ffffff" fill-opacity="0.78" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />

                <!-- Campus Building in Background -->
                <rect x="640" y="150" width="960" height="245" fill="url(#bldgGrad)" />
                <rect x="640" y="142" width="960" height="12" fill="#94a3b8" />
                <!-- Windows Row 1 -->
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
                <!-- Windows Row 2 -->
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

                <!-- Lush Campus Trees -->
                <g>
                    <rect x="145" y="290" width="14" height="90" fill="#78350f" />
                    <circle cx="152" cy="260" r="58" fill="#15803d" />
                    <circle cx="122" cy="276" r="42" fill="#16a34a" />
                    <circle cx="184" cy="276" r="44" fill="#166534" />

                    <rect x="335" y="305" width="12" height="75" fill="#78350f" />
                    <circle cx="341" cy="280" r="48" fill="#16a34a" />
                    <circle cx="315" cy="292" r="34" fill="#15803d" />

                    <rect x="550" y="300" width="14" height="80" fill="#78350f" />
                    <circle cx="557" cy="272" r="52" fill="#15803d" />
                    <circle cx="590" cy="285" r="38" fill="#16a34a" />

                    <circle cx="760" cy="345" r="46" fill="#16a34a" />
                    <circle cx="810" cy="350" r="40" fill="#15803d" />
                    <circle cx="1360" cy="342" r="50" fill="#15803d" />
                    <circle cx="1425" cy="346" r="44" fill="#16a34a" />
                </g>

                <!-- Parked Vehicles Along Lot -->
                <g opacity="0.92">
                    <rect x="655" y="395" width="76" height="36" rx="8" fill="#475569" />
                    <rect x="668" y="400" width="46" height="14" rx="4" fill="#cbd5e1" />

                    <rect x="752" y="396" width="78" height="35" rx="8" fill="#1e293b" />
                    <rect x="766" y="401" width="48" height="14" rx="4" fill="#94a3b8" />

                    <rect x="850" y="395" width="76" height="36" rx="8" fill="#cbd5e1" />
                    <rect x="863" y="400" width="46" height="14" rx="4" fill="#334155" />

                    <rect x="945" y="396" width="80" height="35" rx="8" fill="#64748b" />
                    <rect x="958" y="401" width="50" height="14" rx="4" fill="#e2e8f0" />
                </g>

                <!-- Paved Parking Lot & Driveway -->
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#asphaltGrad)" />

                <!-- Center Shrub Island & Yellow/Black Curb -->
                <polygon points="435,515 780,515 755,565 410,565" fill="#166534" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <g fill="#0f172a">
                    <polygon points="425,565 445,565 440,578 420,578" />
                    <polygon points="465,565 485,565 480,578 460,578" />
                    <polygon points="505,565 525,565 520,578 500,578" />
                    <polygon points="545,565 565,565 560,578 540,578" />
                    <polygon points="585,565 605,565 600,578 580,578" />
                    <polygon points="625,565 645,565 640,578 620,578" />
                    <polygon points="665,565 685,565 680,578 660,578" />
                    <polygon points="705,565 725,565 720,578 700,578" />
                </g>

                <!-- Right Curb with Hazard Stripes -->
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
                <g fill="#0f172a">
                    <rect x="1200" y="635" width="24" height="17" />
                    <rect x="1248" y="635" width="24" height="17" />
                    <rect x="1296" y="635" width="24" height="17" />
                    <rect x="1344" y="635" width="24" height="17" />
                    <rect x="1392" y="635" width="24" height="17" />
                    <rect x="1440" y="635" width="24" height="17" />
                    <rect x="1488" y="635" width="24" height="17" />
                    <rect x="1536" y="635" width="24" height="17" />
                </g>

                <!-- Gate Barrier Posts & Striped Arm -->
                <rect x="742" y="510" width="34" height="86" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
                <rect x="1142" y="535" width="38" height="92" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
                <!-- Barrier Arm -->
                <polygon points="774,548 1144,572 1144,585 774,561" fill="#ffffff" stroke="#0f172a" stroke-width="1.5" />
                <g fill="#dc2626">
                    <polygon points="810,550 845,552 845,565 810,563" />
                    <polygon points="880,555 915,557 915,570 880,568" />
                    <polygon points="950,559 985,561 985,574 950,572" />
                    <polygon points="1020,564 1055,566 1055,579 1020,577" />
                    <polygon points="1090,568 1125,570 1125,583 1090,581" />
                </g>

                <!-- Campus Parking Guardhouse Booth -->
                <g>
                    <!-- Booth Body -->
                    <rect x="1175" y="330" width="255" height="225" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" />
                    <!-- Glass Windows -->
                    <rect x="1196" y="370" width="96" height="84" rx="4" fill="#0f172a" fill-opacity="0.72" />
                    <rect x="1308" y="370" width="102" height="84" rx="4" fill="#0f172a" fill-opacity="0.72" />
                    <!-- Lower Panel -->
                    <rect x="1196" y="472" width="214" height="68" rx="4" fill="#e2e8f0" />
                    <!-- Blue Roof & Sign -->
                    <polygon points="1145,332 1458,332 1435,272 1170,272" fill="#1d4ed8" />
                    <rect x="1175" y="286" width="255" height="44" rx="4" fill="#1e40af" stroke="#ffffff" stroke-width="1.5" />
                    <text x="1302" y="305" text-anchor="middle" fill="#ffffff" font-family="Inter, sans-serif" font-size="15" font-weight="900" letter-spacing="2">CAMPUS</text>
                    <text x="1302" y="322" text-anchor="middle" fill="#93c5fd" font-family="Inter, sans-serif" font-size="13" font-weight="800" letter-spacing="2.5">PARKING</text>
                </g>

                <!-- Street Lamp Pole -->
                <rect x="1488" y="210" width="9" height="430" fill="#cbd5e1" />
                <circle cx="1492" cy="206" r="14" fill="#fef08a" stroke="#94a3b8" stroke-width="2" />
            </svg>

            <!-- Subtle Scrim for High Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/35 via-slate-950/20 to-slate-950/65"></div>
        </div>

        <!-- Main Hero Foreground -->
        <div class="relative z-10 mx-auto flex w-full max-w-7xl flex-1 flex-col justify-between px-4 pt-10 pb-8 sm:px-6 lg:px-8 lg:pt-14">

            <!-- Title Block & Single Get Started Button -->
            <div class="max-w-2xl">
                <p class="font-script-welcome text-4xl text-white sm:text-5xl lg:text-6xl">
                    Welcome to
                </p>

                <h1 class="hero-text-shadow mt-2 text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-[3.5rem] lg:leading-[1.08]">
                    Campus Parking
                    <span class="mt-1 inline-flex items-center gap-3">
                        <!-- Car + Parking Icon beside System -->
                        <svg class="h-9 w-12 text-white drop-shadow-md sm:h-11 sm:w-14" viewBox="0 0 64 44" fill="none" stroke="currentColor" stroke-width="2.6">
                            <path d="M8 29h48" stroke-linecap="round" />
                            <path d="M12 29l3.5-9.5A3 3 0 0 1 18.3 17.5h15.4a3 3 0 0 1 2.8 2L40 29" />
                            <circle cx="17" cy="32" r="3.5" fill="#1d4ed8" />
                            <circle cx="37" cy="32" r="3.5" fill="#1d4ed8" />
                            <rect x="44" y="7" width="14" height="16" rx="2.5" />
                            <path d="M49 19v-7h2.5a1.5 1.5 0 0 1 0 3H49" stroke-linecap="round" />
                            <path d="M51 23v6" stroke-linecap="round" />
                        </svg>
                        <span>System</span>
                    </span>
                </h1>

                <p class="hero-text-shadow mt-4 max-w-lg text-base font-medium leading-relaxed text-white/95 sm:text-lg">
                    A smarter and more convenient way to manage campus parking.
                </p>

                <!-- Single Primary CTA -->
                <div class="mt-6">
                    <a
                        href="{{ $primaryGetStartedUrl }}"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-bold text-white shadow-lg shadow-blue-950/40 transition hover:-translate-y-0.5 hover:bg-blue-500"
                    >
                        <span>Get Started</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- 4 Glass Feature Cards Row -->
            <div class="mt-12 grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ $loginUrl }}" class="glass-feature-card group flex items-center gap-3.5 rounded-2xl p-4 text-left transition hover:bg-slate-900/70">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="17" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                            <path d="M8 14h2v2H8zM14 14h2v2h-2zM8 17h2v1H8zM14 17h2v1h-2z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-white group-hover:text-blue-200">Book Your Slot</h2>
                        <p class="mt-0.5 text-xs text-slate-200/85">Reserve your parking space anytime.</p>
                    </div>
                </a>

                <a href="{{ $loginUrl }}" class="glass-feature-card group flex items-center gap-3.5 rounded-2xl p-4 text-left transition hover:bg-slate-900/70">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 3l8 4v5c0 5-3.4 8.6-8 9.8-4.6-1.2-8-4.8-8-9.8V7l8-4z" />
                            <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-white group-hover:text-blue-200">Secure Access</h2>
                        <p class="mt-0.5 text-xs text-slate-200/85">Safe and reliable system.</p>
                    </div>
                </a>

                <a href="{{ $loginUrl }}" class="glass-feature-card group flex items-center gap-3.5 rounded-2xl p-4 text-left transition hover:bg-slate-900/70">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13 2L4.5 13.5H11L10 22l8.5-11.5H13L13 2z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-white group-hover:text-blue-200">Real-Time Updates</h2>
                        <p class="mt-0.5 text-xs text-slate-200/85">Check availability in real-time.</p>
                    </div>
                </a>

                <button type="button" onclick="window.openContactModal()" class="glass-feature-card group flex items-center gap-3.5 rounded-2xl p-4 text-left transition hover:bg-slate-900/70 cursor-pointer">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 13a8 8 0 0 1 16 0" />
                            <rect x="3" y="13" width="4" height="6" rx="1.5" />
                            <rect x="17" y="13" width="4" height="6" rx="1.5" />
                            <path d="M17 19a3 3 0 0 1-3 2h-2" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-white group-hover:text-blue-200">Our Support</h2>
                        <p class="mt-0.5 text-xs text-slate-200/85">We&apos;re here to help you.</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Bottom Wave Accent -->
        <div class="pointer-events-none relative z-10 w-full leading-none">
            <svg class="h-8 w-full sm:h-11" viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,25 C360,72 1080,-10 1440,35 L1440,70 L0,70 Z" fill="#1d4ed8" />
            </svg>
        </div>
    </section>

    <!-- Footer with Terms & Conditions -->
    <footer class="border-t border-blue-900/60 bg-blue-950 py-5 text-xs text-blue-100/85">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 sm:flex-row sm:px-6 lg:px-8">
            <span class="font-semibold text-white">Campus Parking System &bull; Park Smart. Move Better.</span>
            <div class="flex items-center gap-5">
                <a
                    href="{{ $termsUrl }}"
                    class="font-semibold text-blue-200 underline decoration-blue-400/60 underline-offset-4 transition hover:text-white"
                >
                    Terms &amp; Conditions
                </a>
                <span>&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
        </div>
    </footer>

    <!-- Support / Contact Modal -->
    <div
        id="cs_contact_modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/75 p-4 backdrop-blur-xs"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cs_contact_title"
        onclick="if (event.target === this) window.closeContactModal();"
    >
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-800 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600">Assistance</p>
                    <h3 id="cs_contact_title" class="text-lg font-black text-slate-950">Parking &amp; Gate Support</h3>
                </div>
                <button
                    type="button"
                    onclick="window.closeContactModal()"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 cursor-pointer"
                    aria-label="Close"
                >
                    &times;
                </button>
            </div>

            <div class="space-y-3 px-6 py-5 text-sm text-slate-600">
                <p>Need help with gate entry, slot assignment, or account access? Reach out to campus parking operations:</p>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3.5 text-xs space-y-1.5">
                    <p><span class="font-bold text-slate-900">Gate Desk:</span> Main Gate Guardhouse (Open 24/7)</p>
                    <p><span class="font-bold text-slate-900">Email:</span> parking-support@campus.edu</p>
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-slate-100 bg-slate-50 px-6 py-3.5">
                <button
                    type="button"
                    onclick="window.closeContactModal()"
                    class="inline-flex h-10 items-center justify-center rounded-xl bg-blue-600 px-5 text-xs font-extrabold text-white transition hover:bg-blue-700 cursor-pointer"
                >
                    Got It
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function toggleModal(id, show) {
                var el = document.getElementById(id);
                if (!el) return;
                if (show) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            window.openContactModal = function () { toggleModal('cs_contact_modal', true); };
            window.closeContactModal = function () { toggleModal('cs_contact_modal', false); };

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    window.closeContactModal();
                }
            });
        })();
    </script>
</body>
</html>