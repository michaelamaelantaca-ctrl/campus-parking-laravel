<x-app-layout>
    <style>
        .vlogs-shell,
        .vlogs-shell * {
            box-sizing: border-box;
        }

        .vlogs-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .vlogs-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .vlogs-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .vlogs-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
        }

        .vlogs-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 12px;
            font-weight: 800;
        }

        .vlogs-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .vlogs-table thead th {
            padding: 13px 18px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        .vlogs-table tbody td {
            padding: 15px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .vlogs-table tbody tr:hover {
            background: #f8fafc;
        }
    </style>

    @php
        $logsCollection = collect($logs ?? $vehicleLogs ?? []);
        $totalCount = $logsCollection->count();
        $checkedInCount = $logsCollection->filter(function ($l) {
            return strtolower((string) ($l->status ?? '')) === 'checked-in';
        })->count();
        $checkedOutCount = $logsCollection->filter(function ($l) {
            return strtolower((string) ($l->status ?? '')) === 'checked-out';
        })->count();

        $createLogUrl = \Illuminate\Support\Facades\Route::has('admin.vehicle-logs.create')
            ? route('admin.vehicle-logs.create')
            : url('/admin/vehicle-logs/create');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-700">Parking Operations</p>
                <h2 class="mt-0.5 text-2xl font-black tracking-tight text-slate-950">Vehicle Logs Management</h2>
            </div>

            <a
                href="{{ $createLogUrl }}"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-sm font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800"
            >
                <span>+ Add New Vehicle Log</span>
            </a>
        </div>
    </x-slot>

    <div
        x-data="{
            search: '',
            statusTab: 'All',
            matchesLog(blob, statusVal) {
                const matchTab = this.statusTab === 'All' || String(statusVal).toLowerCase() === String(this.statusTab).toLowerCase();
                const q = this.search.trim().toLowerCase();
                const matchQuery = !q || String(blob).toLowerCase().includes(q);
                return matchTab && matchQuery;
            }
        }"
        class="vlogs-shell min-h-[calc(100vh-64px)] py-8 sm:py-10"
    >
        <!-- Welcome-Style Campus Scene Background -->
        <div class="vlogs-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="vlgBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="vlgBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="vlgBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#vlgBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#vlgBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#vlgBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="vlogs-scene-scrim"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900 shadow-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-900 shadow-xs">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Summary Strip -->
            <div class="vlogs-card flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-600 mr-1">Logs Summary:</span>
                    <span class="vlogs-stat-pill">Total Logs: <strong>{{ $totalCount }}</strong></span>
                    <span class="vlogs-stat-pill">Checked-In: <strong>{{ $checkedInCount }}</strong></span>
                    <span class="vlogs-stat-pill">Checked-Out: <strong>{{ $checkedOutCount }}</strong></span>
                </div>

                <a
                    href="{{ $createLogUrl }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-xs font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800"
                >
                    <span>+ Add New Vehicle Log</span>
                </a>
            </div>

            <!-- Main Logs Card -->
            <div class="vlogs-card overflow-hidden">
                <!-- Search & Status Tabs -->
                <div class="border-b border-slate-200 px-6 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative w-full sm:max-w-md">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search plate number, driver, or vehicle..."
                            class="w-full h-10 rounded-xl border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600"
                        >
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="statusTab = 'All'"
                            :class="statusTab === 'All' ? 'bg-blue-700 text-white border-blue-700 shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="h-10 px-4 rounded-xl border text-xs font-extrabold transition cursor-pointer"
                        >All</button>
                        <button
                            type="button"
                            @click="statusTab = 'Checked-In'"
                            :class="statusTab === 'Checked-In' ? 'bg-emerald-700 text-white border-emerald-700 shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="h-10 px-4 rounded-xl border text-xs font-extrabold transition cursor-pointer"
                        >Checked-In</button>
                        <button
                            type="button"
                            @click="statusTab = 'Checked-Out'"
                            :class="statusTab === 'Checked-Out' ? 'bg-slate-800 text-white border-slate-800 shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="h-10 px-4 rounded-xl border text-xs font-extrabold transition cursor-pointer"
                        >Checked-Out</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="vlogs-table">
                        <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver Name</th>
                                <th>Vehicle Type</th>
                                <th>Zone / Slot</th>
                                <th>Time In</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logsCollection as $log)
                                @php
                                    $plate = strtoupper((string) ($log->plate_number ?? ''));
                                    $driverName = $log->driver_name ?? ($log->user->name ?? 'Walk-in Driver');
                                    $initials = strtoupper(substr(trim((string) $driverName), 0, 1) ?: 'D');
                                    $vehicleType = $log->vehicle_type ?? 'Car';

                                    $zoneName = $log->zone->name ?? ($log->parkingZone->name ?? ($log->slot->zone->name ?? null));
                                    $slotNumber = $log->slot->slot_number ?? ($log->parkingSlot->slot_number ?? null);
                                    $zoneSlotLabel = ($zoneName && $slotNumber)
                                        ? ($zoneName . ' • ' . $slotNumber)
                                        : ($zoneName ?: ($slotNumber ?: 'Unassigned'));
                                    $isUnassigned = $zoneSlotLabel === 'Unassigned';

                                    $timeInRaw = $log->time_in ?? ($log->checked_in_at ?? ($log->created_at ?? ''));
                                    $timeInFormatted = is_object($timeInRaw) && method_exists($timeInRaw, 'format')
                                        ? $timeInRaw->format('Y-m-d H:i')
                                        : substr((string) $timeInRaw, 0, 16);

                                    $statusVal = $log->status ?? 'Checked-In';
                                    $isCheckedIn = strtolower((string) $statusVal) === 'checked-in';

                                    $editLogUrl = \Illuminate\Support\Facades\Route::has('admin.vehicle-logs.edit')
                                        ? route('admin.vehicle-logs.edit', $log)
                                        : url('/admin/vehicle-logs/' . ($log->id ?? '') . '/edit');

                                    $deleteLogUrl = \Illuminate\Support\Facades\Route::has('admin.vehicle-logs.destroy')
                                        ? route('admin.vehicle-logs.destroy', $log)
                                        : url('/admin/vehicle-logs/' . ($log->id ?? ''));

                                    $searchBlob = implode(' ', [$plate, $driverName, $vehicleType, $zoneSlotLabel, $statusVal]);
                                @endphp

                                <tr x-show="matchesLog(@js($searchBlob), @js($statusVal))">
                                    <td>
                                        <span class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-black tracking-wide text-slate-900">
                                            {{ $plate }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-slate-900 text-xs font-black text-white">
                                                {{ $initials }}
                                            </span>
                                            <span class="font-bold text-slate-950">{{ $driverName }}</span>
                                        </div>
                                    </td>
                                    <td class="text-slate-700 font-bold">{{ $vehicleType }}</td>
                                    <td>
                                        @if($isUnassigned)
                                            <span class="inline-flex rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                                Unassigned
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700">
                                                {{ $zoneSlotLabel }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-xs font-bold text-slate-600 whitespace-nowrap">{{ $timeInFormatted }}</td>
                                    <td>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold {{ $isCheckedIn ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-slate-200 bg-slate-100 text-slate-700' }}">
                                            {{ $statusVal }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ $editLogUrl }}"
                                                class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 text-xs font-extrabold text-amber-800 transition hover:bg-amber-100"
                                            >
                                                <span>✎</span>
                                                <span>Edit</span>
                                            </a>
                                            <form
                                                action="{{ $deleteLogUrl }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this vehicle log?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 text-xs font-extrabold text-rose-700 transition hover:bg-rose-100 cursor-pointer"
                                                >
                                                    <span>🗑</span>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-sm font-semibold text-slate-500">
                                        No vehicle logs recorded yet.
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