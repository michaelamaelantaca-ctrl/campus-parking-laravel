<x-app-layout>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122polished">

    <style>
        .zones-mgmt-shell,
        .zones-mgmt-shell * {
            box-sizing: border-box;
        }

        .zones-mgmt-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .zones-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .zones-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .zones-mgmt-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .zones-mgmt-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 12px;
            font-weight: 800;
        }
    </style>

    @php
        $zonesCollection = collect($zones ?? []);
        $totalZonesCount = $zonesCollection->count();
        $activeZonesCount = $zonesCollection->where('is_active', true)->count();
        $totalCapacitySum = $zonesCollection->sum(fn($z) => (int) ($z->total_capacity ?? 0));
        $availableSlotsSum = $zonesCollection->sum(fn($z) => (int) ($z->available_slots ?? 0));
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Campus Configuration</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Parking Zones</h2>
            </div>
            <a href="{{ route('admin.zones.create') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-sm font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Add New Zone
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            search: '',
            statusFilter: 'all',
            matchesZone(blob, isActive) {
                const statusLabel = isActive ? 'active' : 'disabled';
                const matchStatus = this.statusFilter === 'all' || this.statusFilter === statusLabel;
                const q = this.search.trim().toLowerCase();
                const matchQuery = !q || blob.toLowerCase().includes(q);
                return matchStatus && matchQuery;
            }
        }"
        class="zones-mgmt-shell min-h-[calc(100vh-64px)] py-8 sm:py-10"
    >
        <!-- Welcome-Style Campus Scene Background -->
        <div class="zones-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="znsBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="znsBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="znsBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#znsBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#znsBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#znsBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="zones-scene-scrim"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900 shadow-xs">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Strip -->
            <div class="zones-mgmt-card flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-600 mr-1">Zones Summary:</span>
                    <span class="zones-mgmt-chip">Total Zones: <strong>{{ $totalZonesCount }}</strong></span>
                    <span class="zones-mgmt-chip">Active: <strong>{{ $activeZonesCount }}</strong></span>
                    <span class="zones-mgmt-chip">Total Capacity: <strong>{{ $totalCapacitySum }}</strong></span>
                    <span class="zones-mgmt-chip">Available Slots: <strong>{{ $availableSlotsSum }}</strong></span>
                </div>

                <a href="{{ route('admin.zones.create') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-xs font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Add New Zone
                </a>
            </div>

            <!-- Main Zones Table Card -->
            <div class="zones-mgmt-card overflow-hidden">
                <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h3 class="text-base font-black text-slate-950">Campus parking zones</h3>
                            <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-0.5 text-xs font-extrabold text-blue-700">{{ $totalZonesCount }} zones</span>
                        </div>
                        <p class="mt-0.5 text-xs font-semibold text-slate-500">Manage capacity, availability, locations, and slot status.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search zone or landmark..."
                            class="h-9 w-56 rounded-xl border-slate-300 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                        >
                        <button
                            type="button"
                            @click="statusFilter = 'all'"
                            :class="statusFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="h-9 rounded-xl px-3 text-xs font-extrabold transition"
                        >All</button>
                        <button
                            type="button"
                            @click="statusFilter = 'active'"
                            :class="statusFilter === 'active' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                            class="h-9 rounded-xl px-3 text-xs font-extrabold transition"
                        >Active</button>
                        <button
                            type="button"
                            @click="statusFilter = 'disabled'"
                            :class="statusFilter === 'disabled' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="h-9 rounded-xl px-3 text-xs font-extrabold transition"
                        >Disabled</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3.5 text-left">Zone Name</th>
                                <th class="px-5 py-3.5 text-center">Total Capacity</th>
                                <th class="px-5 py-3.5 text-center">Available</th>
                                <th class="px-5 py-3.5 text-center">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($zones as $zone)
                                @php
                                    $landmarkText = $zone->landmark ?: 'Campus parking zone';
                                    $zoneSearchBlob = implode(' ', [
                                        $zone->name ?? '',
                                        $landmarkText,
                                        $zone->is_active ? 'active' : 'disabled',
                                    ]);
                                @endphp
                                <tr x-show="matchesZone(@js($zoneSearchBlob), @js((bool) $zone->is_active))" class="transition hover:bg-blue-50/40">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl border border-blue-200 bg-blue-50 text-xs font-black text-blue-700">
                                                {{ strtoupper(substr($zone->name, 0, 2)) }}
                                            </span>
                                            <div>
                                                <p class="font-extrabold text-slate-950">{{ $zone->name }}</p>
                                                <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $landmarkText }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center font-black text-slate-800">{{ $zone->total_capacity ?? 0 }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-sm font-black text-emerald-700">
                                            {{ $zone->available_slots ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-extrabold {{ $zone->is_active ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-slate-200 bg-slate-100 text-slate-600' }}">
                                            {{ $zone->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.zones.slots.index', $zone->id) }}" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 text-xs font-extrabold text-emerald-800 transition hover:bg-emerald-100">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM8 3v18M16 3v18"/></svg>
                                                View Slots
                                            </a>
                                            <a href="{{ route('admin.zones.edit', $zone->id) }}" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 text-xs font-extrabold text-blue-700 transition hover:bg-blue-100">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.zones.toggle', $zone->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex h-9 items-center rounded-xl border border-amber-200 bg-amber-50 px-3 text-xs font-extrabold text-amber-800 transition hover:bg-amber-100 cursor-pointer">
                                                    {{ $zone->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('Delete this parking zone?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 text-xs font-extrabold text-rose-700 transition hover:bg-rose-100 cursor-pointer">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V3h8v3M19 6l-1 15H6L5 6"/></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm font-semibold text-slate-500">
                                        No parking zones found.
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