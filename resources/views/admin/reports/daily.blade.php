<x-app-layout>
    <style>
        .daily-audit-shell,
        .daily-audit-shell * {
            box-sizing: border-box;
        }

        .daily-audit-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .daily-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .daily-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .daily-audit-hero {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(135deg, #090d16 0%, #172554 55%, #1d4ed8 100%) !important;
            padding: 24px 28px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.14);
        }

        .daily-audit-hero .hero-eyebrow {
            color: #93c5fd !important;
            -webkit-text-fill-color: #93c5fd !important;
        }

        .daily-audit-hero .hero-title {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .daily-audit-hero .hero-sub {
            color: #e2e8f0 !important;
            -webkit-text-fill-color: #e2e8f0 !important;
        }

        .daily-audit-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .daily-kpi-card {
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px 22px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .daily-kpi-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        }

        .daily-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .daily-table thead th {
            padding: 13px 20px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        .daily-table tbody td {
            padding: 15px 20px;
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .daily-table tbody tr:hover {
            background: #f8fafc;
        }

        @media print {
            .daily-scene-bg,
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
            }
            .daily-audit-card,
            .daily-kpi-card,
            .daily-audit-hero {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
            }
        }
    </style>

    <div
        x-data="{
            search: '',
            statusFilter: 'All',
            matchesRow(text, status) {
                const matchStatus = this.statusFilter === 'All' || status === this.statusFilter;
                const q = this.search.trim().toLowerCase();
                const matchQuery = !q || text.toLowerCase().includes(q);
                return matchStatus && matchQuery;
            }
        }"
        class="daily-audit-shell min-h-[calc(100vh-64px)] py-8"
    >
        <!-- Welcome-Style Campus Scene Background -->
        <div class="daily-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="dlyBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="dlyBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="dlyBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#dlyBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#dlyBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#dlyBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="daily-scene-scrim"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Banner -->
            <section class="daily-audit-hero">
                <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full border border-white/10 bg-white/5"></div>
                <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="hero-eyebrow text-[11px] font-black uppercase tracking-[0.18em]">Audit &amp; Activity Logs</p>
                        <h2 class="hero-title mt-1 text-2xl font-black tracking-tight sm:text-3xl">{{ __('Daily Parking Audit Report') }}</h2>
                        <p class="hero-sub mt-1.5 text-sm font-medium">
                            Daily summary for <strong class="font-extrabold text-white">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</strong> — check-ins, check-outs, guards, and zones.
                        </p>
                    </div>
                    <div class="no-print flex items-center gap-2.5">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-white/25 bg-white/10 px-4 text-xs font-extrabold text-white transition hover:bg-white/20"
                        >
                            Print Report
                        </button>
                    </div>
                </div>
            </section>

            <!-- Date Filter Card -->
            <div class="daily-audit-card p-6 no-print">
                <form method="GET" action="{{ route('admin.reports.daily') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
                    <div>
                        <label for="date" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-2">Report Date</label>
                        <input
                            id="date"
                            type="date"
                            name="date"
                            value="{{ $date }}"
                            class="h-10 rounded-xl border-slate-300 text-sm font-bold text-slate-900 focus:border-blue-600 focus:ring-blue-600"
                        >
                    </div>
                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center px-5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-sm font-extrabold shadow-xs transition"
                    >
                        Generate Report
                    </button>
                    <a
                        href="{{ route('admin.vehicle-logs.index') }}"
                        class="inline-flex h-10 items-center justify-center px-4 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-800 text-sm font-extrabold transition"
                    >
                        All Vehicle Logs
                    </a>
                </form>
            </div>

            <!-- KPI Summary Strip -->
            <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-4 lg:gap-5">
                <div class="daily-kpi-card">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-blue-600"></div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Entries</span>
                    <strong class="block text-3xl font-black text-blue-700 mt-2">{{ $summary['entries'] }}</strong>
                </div>

                <div class="daily-kpi-card">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-emerald-600"></div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Checked Out</span>
                    <strong class="block text-3xl font-black text-emerald-700 mt-2">{{ $summary['checked_out'] }}</strong>
                </div>

                <div class="daily-kpi-card">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-amber-500"></div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Currently Parked</span>
                    <strong class="block text-3xl font-black text-amber-700 mt-2">{{ $summary['currently_parked'] }}</strong>
                </div>

                <div class="daily-kpi-card">
                    <div class="absolute left-0 top-0 h-full w-1.5 bg-indigo-600"></div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Zones Used</span>
                    <strong class="block text-3xl font-black text-indigo-700 mt-2">{{ $summary['zones_used'] }}</strong>
                </div>
            </div>

            <!-- Vehicle Activity Table Card -->
            <div class="daily-audit-card overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-950">Vehicle Activity — {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">Showing logs recorded on the selected audit date.</p>
                    </div>

                    <div class="no-print flex flex-wrap items-center gap-2">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search plate, driver, zone, guard..."
                            class="h-9 w-56 rounded-xl border-slate-300 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                        >
                        <button
                            type="button"
                            @click="statusFilter = 'All'"
                            :class="statusFilter === 'All' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >All</button>
                        <button
                            type="button"
                            @click="statusFilter = 'Checked-In'"
                            :class="statusFilter === 'Checked-In' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >Checked-In</button>
                        <button
                            type="button"
                            @click="statusFilter = 'Checked-Out'"
                            :class="statusFilter === 'Checked-Out' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                            class="h-9 px-3 rounded-xl text-xs font-extrabold transition"
                        >Checked-Out</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="daily-table">
                        <thead>
                            <tr>
                                <th>Plate</th>
                                <th>Driver</th>
                                <th>Zone / Slot</th>
                                <th>Guard</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $guardObj = $log->securityGuard ?? null;
                                    $guardDisplay = $guardObj->name ?? 'N/A';
                                    $guardGateDisplay = $guardObj->assigned_gate ?? null;
                                    $searchBlob = implode(' ', [
                                        $log->plate_number ?? '',
                                        $log->driver_name ?? '',
                                        $log->zone->name ?? '',
                                        $log->slot->slot_number ?? '',
                                        $guardDisplay,
                                        $guardGateDisplay ?? '',
                                    ]);
                                @endphp
                                <tr x-show="matchesRow(@js($searchBlob), @js($log->status))">
                                    <td class="font-black text-slate-950">{{ $log->plate_number }}</td>
                                    <td class="text-slate-700">{{ $log->driver_name ?? 'N/A' }}</td>
                                    <td class="text-slate-700">{{ $log->zone->name ?? 'N/A' }} / <span class="font-bold text-slate-900">{{ $log->slot->slot_number ?? 'N/A' }}</span></td>
                                    <td class="text-slate-700">
                                        <div>{{ $guardDisplay }}</div>
                                        @if (!empty($guardGateDisplay))
                                            <div class="text-[11px] font-bold text-blue-700">{{ $guardGateDisplay }}</div>
                                        @endif
                                    </td>
                                    <td class="text-slate-700">{{ $log->time_in?->format('h:i A') }}</td>
                                    <td class="text-slate-700">{{ $log->time_out?->format('h:i A') ?? '—' }}</td>
                                    <td>
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-extrabold {{ $log->status === 'Checked-In' ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-emerald-100 text-emerald-900 border border-emerald-200' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-sm font-semibold text-slate-500">
                                        No parking activity recorded for this date.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>