@extends('layouts.app')

@section('content')
<style>
    .admin-dash-shell,
    .admin-dash-shell * {
        box-sizing: border-box;
    }

    .admin-dash-shell {
        font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
        font-variant-numeric: tabular-nums;
    }

    .admin-scene-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .admin-scene-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.36) 0%, rgba(15, 23, 42, 0.48) 100%);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    .admin-dash-content {
        position: relative;
        z-index: 10;
    }

    .admin-hero-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, #090d16 0%, #172554 52%, #1d4ed8 100%) !important;
        padding: 28px 32px;
        box-shadow: 0 18px 38px rgba(9, 13, 22, 0.32);
        border: 1px solid rgba(255, 255, 255, 0.16);
    }

    .admin-hero-card .hero-eyebrow {
        color: #93c5fd !important;
        -webkit-text-fill-color: #93c5fd !important;
    }

    .admin-hero-card .hero-title {
        color: #ffffff !important;
        -webkit-text-fill-color: #ffffff !important;
    }

    .admin-hero-card .hero-subtitle {
        color: #e2e8f0 !important;
        -webkit-text-fill-color: #e2e8f0 !important;
    }

    .admin-hero-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 44px;
        padding: 0 20px;
        border-radius: 12px;
        background: #ffffff !important;
        color: #0f172a !important;
        -webkit-text-fill-color: #0f172a !important;
        font-size: 13.5px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.24);
        transition: transform 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease;
    }

    .admin-hero-cta:hover {
        transform: translateY(-2px);
        background: #eff6ff !important;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.32);
    }

    .admin-stat-box {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        border: 1.5px solid rgba(255, 255, 255, 0.72);
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 20px 22px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .admin-stat-box:hover {
        transform: translateY(-2px);
        border-color: #ffffff;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.2);
    }

    .admin-panel-card {
        border-radius: 18px;
        border: 1.5px solid rgba(255, 255, 255, 0.72);
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
    }

    .admin-weather-box {
        border-radius: 14px;
        border: 1px solid #bae6fd;
        background: linear-gradient(180deg, #f0f9ff 0%, #f8fafc 100%);
        padding: 16px;
        transition: transform 0.16s ease, border-color 0.16s ease, background-color 0.16s ease;
    }

    .admin-weather-box:hover {
        transform: translateY(-1px);
        border-color: #7dd3fc;
        background: #ffffff;
    }
</style>

<div class="admin-dash-shell relative min-h-[calc(100vh-64px)] bg-slate-950 py-8">

    <!-- Campus Parking Gate & Lot Background Scene -->
    <div class="admin-scene-bg" aria-hidden="true">
        <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="dashSkyGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#3b82f6" />
                    <stop offset="55%" stop-color="#93c5fd" />
                    <stop offset="100%" stop-color="#e0f2fe" />
                </linearGradient>
                <linearGradient id="dashAsphaltGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#475569" />
                    <stop offset="45%" stop-color="#334155" />
                    <stop offset="100%" stop-color="#1e293b" />
                </linearGradient>
                <linearGradient id="dashBldgGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#f8fafc" />
                    <stop offset="100%" stop-color="#cbd5e1" />
                </linearGradient>
            </defs>

            <!-- Sky & Clouds -->
            <rect width="1600" height="440" fill="url(#dashSkyGrad)" />
            <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
            <ellipse cx="475" cy="108" rx="68" ry="22" fill="#ffffff" fill-opacity="0.78" />
            <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />

            <!-- Campus Building -->
            <rect x="640" y="150" width="960" height="245" fill="url(#dashBldgGrad)" />
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
            <g opacity="0.92">
                <rect x="655" y="395" width="76" height="36" rx="8" fill="#475569" />
                <rect x="752" y="396" width="78" height="35" rx="8" fill="#1e293b" />
                <rect x="850" y="395" width="76" height="36" rx="8" fill="#cbd5e1" />
                <rect x="945" y="396" width="80" height="35" rx="8" fill="#64748b" />
            </g>

            <!-- Asphalt Lot & Curbs -->
            <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#dashAsphaltGrad)" />
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
        <div class="admin-scene-scrim"></div>
    </div>

    <div class="admin-dash-content mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <section class="admin-hero-card">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full border border-white/10 bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-24 right-24 h-44 w-44 rounded-full bg-blue-400/10 blur-xl"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="hero-eyebrow mb-2 text-[11px] font-black uppercase tracking-[0.18em]">System Overview</p>
                    <h1 class="hero-title text-2xl font-black tracking-tight sm:text-3xl">Admin Dashboard</h1>
                    <p class="hero-subtitle mt-2 max-w-xl text-sm font-medium leading-6">
                        Configure campus parking zones, manage guard accounts, and inspect live parking activity.
                    </p>
                </div>

                <a href="{{ route('admin.zones.index') }}" class="admin-hero-cta self-start sm:self-auto">
                    <span>Manage Parking Zones</span>
                    <span>→</span>
                </a>
            </div>
        </section>

        @if(isset($usage))
            <section class="grid grid-cols-2 gap-3.5 lg:grid-cols-4 lg:gap-5">
                <div class="admin-stat-box">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-blue-600"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Active Zones</p>
                            <strong class="mt-2 block text-3xl font-black text-slate-950">{{ $usage['active_zones'] }} / {{ $usage['zones'] }}</strong>
                        </div>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-sm font-black text-blue-700 ring-1 ring-blue-100">⌖</span>
                    </div>
                </div>

                <div class="admin-stat-box">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-emerald-500"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Available Slots</p>
                            <strong class="mt-2 block text-3xl font-black text-slate-950">{{ $usage['available_slots'] }}</strong>
                        </div>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-50 text-sm font-black text-emerald-700 ring-1 ring-emerald-100">P</span>
                    </div>
                </div>

                <div class="admin-stat-box">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-rose-500"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Occupied Slots</p>
                            <strong class="mt-2 block text-3xl font-black text-slate-950">{{ $usage['occupied_slots'] }}</strong>
                        </div>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-rose-50 text-sm font-black text-rose-600 ring-1 ring-rose-100">●</span>
                    </div>
                </div>

                <div class="admin-stat-box">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-indigo-600"></div>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Today's Entries</p>
                            <strong class="mt-2 block text-3xl font-black text-slate-950">{{ $usage['today_entries'] }}</strong>
                        </div>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-indigo-50 text-sm font-black text-indigo-700 ring-1 ring-indigo-100">↗</span>
                    </div>
                </div>
            </section>
        @endif

        @if(isset($weather))
            <section class="admin-panel-card">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-500">Live Weather</p>
                        <p class="mt-1 text-base font-extrabold text-slate-950">Arakan, North Cotabato</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-[10.5px] font-black uppercase tracking-wider text-sky-700">
                        <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                        Live Weather
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3.5 md:grid-cols-5">
                    <div class="admin-weather-box">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs font-black text-sky-700 shadow-xs ring-1 ring-sky-100">⌖</div>
                        <p class="mt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Location</p>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $weather['location'] }}</p>
                    </div>

                    <div class="admin-weather-box">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs font-black text-sky-700 shadow-xs ring-1 ring-sky-100">°C</div>
                        <p class="mt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Temperature</p>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $weather['temperature'] }}</p>
                    </div>

                    <div class="admin-weather-box">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs font-black text-sky-700 shadow-xs ring-1 ring-sky-100">☁</div>
                        <p class="mt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Condition</p>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $weather['condition'] }}</p>
                    </div>

                    <div class="admin-weather-box">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs font-black text-sky-700 shadow-xs ring-1 ring-sky-100">◌</div>
                        <p class="mt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Humidity</p>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $weather['humidity'] }}</p>
                    </div>

                    <div class="admin-weather-box">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-xs font-black text-sky-700 shadow-xs ring-1 ring-sky-100">≋</div>
                        <p class="mt-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Wind Speed</p>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $weather['wind_speed'] }}</p>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>
@endsection