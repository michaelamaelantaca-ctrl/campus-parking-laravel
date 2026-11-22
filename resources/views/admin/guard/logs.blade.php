<x-app-layout>
    <style>
        .guard-logs-shell {
            position: relative;
            background-color: #0f172a;
            min-height: 100vh;
            padding: 2rem 0;
            color: #0f172a;
            overflow-x: hidden;
        }
        .guard-logs-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .guard-logs-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .guard-logs-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }
        .guard-logs-header {
            background-color: #0f172a !important;
            padding: 18px 24px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }
        .guard-logs-title {
            margin: 0;
            font-size: 19px;
            font-weight: 900;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            letter-spacing: -0.01em;
        }
        .guard-logs-search {
            background-color: #ffffff !important;
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            min-width: 210px;
        }
        .guard-logs-search::placeholder {
            color: #64748b;
        }
        .guard-filter-btn {
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 800;
            border: 1.5px solid #475569;
            background-color: #1e293b !important;
            color: #f8fafc !important;
            -webkit-text-fill-color: #f8fafc !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .guard-filter-btn:hover {
            background-color: #334155 !important;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        .guard-filter-btn.is-active-all {
            background-color: #2563eb !important;
            border-color: #3b82f6;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        .guard-filter-btn.is-active-parked {
            background-color: #059669 !important;
            border-color: #10b981;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        .guard-filter-btn.is-active-out {
            background-color: #475569 !important;
            border-color: #94a3b8;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        .guard-zone-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #0f172a !important;
            font-size: 12.5px;
            font-weight: 800;
        }
        .guard-slot-tag {
            color: #1d4ed8 !important;
            font-weight: 900;
        }
        .guard-checkout-btn {
            background-color: #dc2626 !important;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            padding: 7px 14px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 800;
            border: 0;
            cursor: pointer;
        }
        .guard-checkout-btn:hover {
            background-color: #b91c1c !important;
        }
    </style>

    @php
        $zonesLookup = \App\Models\ParkingZone::pluck('name', 'id');
        $slotsLookup = \App\Models\ParkingSlot::pluck('slot_number', 'id');
    @endphp

    <div class="guard-logs-shell" x-data="{
        search: '',
        filter: 'All',
        matchesLog(item) {
            const q = (this.search || '').toLowerCase().trim();
            const matchesText = !q || String(item.plate || '').toLowerCase().includes(q) || String(item.driver || '').toLowerCase().includes(q);
            if (!matchesText) return false;
            if (this.filter === 'Parked') return item.status === 'Checked-In';
            if (this.filter === 'Checked-Out') return item.status === 'Checked-Out';
            return true;
        },
        duration(timeInStr, timeOutStr) {
            if (!timeInStr) return '—';
            const start = new Date(String(timeInStr).replace(' ', 'T'));
            if (isNaN(start.getTime())) return '—';
            const end = timeOutStr ? new Date(String(timeOutStr).replace(' ', 'T')) : new Date();
            const endTime = isNaN(end.getTime()) ? Date.now() : end.getTime();
            const mins = Math.max(0, Math.floor((endTime - start.getTime()) / 60000));
            const hrs = Math.floor(mins / 60);
            return hrs > 0 ? `${hrs}h ${mins % 60}m` : `${mins}m`;
        }
    }">
        <!-- Welcome-Style Campus Scene Background -->
        <div class="guard-logs-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="grdLogsBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="grdLogsBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="grdLogsBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#grdLogsBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#grdLogsBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#grdLogsBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="guard-logs-scene-scrim"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border-2 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-extrabold text-emerald-950">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border-2 border-rose-500 bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-950">
                    {{ session('error') }}
                </div>
            @endif

            <div class="guard-logs-card">
                <div class="guard-logs-header">
                    <h1 class="guard-logs-title">Real-Time Gate Logs</h1>

                    <div class="flex flex-wrap items-center gap-2">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search plate or driver..."
                            class="guard-logs-search"
                        >
                        <button
                            type="button"
                            @click="filter = 'All'"
                            :class="filter === 'All' ? 'is-active-all' : ''"
                            class="guard-filter-btn"
                        >
                            All
                        </button>
                        <button
                            type="button"
                            @click="filter = 'Parked'"
                            :class="filter === 'Parked' ? 'is-active-parked' : ''"
                            class="guard-filter-btn"
                        >
                            Parked
                        </button>
                        <button
                            type="button"
                            @click="filter = 'Checked-Out'"
                            :class="filter === 'Checked-Out' ? 'is-active-out' : ''"
                            class="guard-filter-btn"
                        >
                            Checked-Out
                        </button>
                    </div>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-black uppercase tracking-wider text-slate-800 bg-slate-100">
                                <th class="px-4 py-3.5">Plate</th>
                                <th class="px-4 py-3.5">Driver</th>
                                <th class="px-4 py-3.5">Vehicle</th>
                                <th class="px-4 py-3.5">Zone / Slot</th>
                                <th class="px-4 py-3.5">Time In</th>
                                <th class="px-4 py-3.5">Duration</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($logs ?? [] as $log)
                                @php
                                    $logPlate = $log->plate_number ?? '';
                                    $logDriver = $log->driver_name ?? ($log->user->name ?? 'Walk-in');
                                    $logStatus = $log->status ?? ($log->time_out ? 'Checked-Out' : 'Checked-In');

                                    $zoneName = $log->zone->name
                                        ?? ($log->parkingZone->name
                                        ?? ($log->zone_name
                                        ?? ($log->parking_zone_id ? ($zonesLookup[$log->parking_zone_id] ?? null) : null)));

                                    $slotNumber = $log->slot->slot_number
                                        ?? ($log->parkingSlot->slot_number
                                        ?? ($log->slot_number
                                        ?? ($log->parking_slot_id ? ($slotsLookup[$log->parking_slot_id] ?? null) : null)));
                                @endphp
                                <tr class="hover:bg-slate-50 transition" x-show="matchesLog({ plate: @js($logPlate), driver: @js($logDriver), status: @js($logStatus) })">
                                    <td class="px-4 py-3.5 font-black text-slate-950">{{ $logPlate }}</td>
                                    <td class="px-4 py-3.5 font-bold text-slate-900">{{ $logDriver }}</td>
                                    <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $log->vehicle_type ?? '—' }}</td>
                                    <td class="px-4 py-3.5">
                                        @if($zoneName || $slotNumber)
                                            <span class="guard-zone-badge">
                                                @if($zoneName)
                                                    <span>{{ $zoneName }}</span>
                                                @endif
                                                @if($zoneName && $slotNumber)
                                                    <span class="text-slate-400">·</span>
                                                @endif
                                                @if($slotNumber)
                                                    <span class="guard-slot-tag">{{ $slotNumber }}</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="font-bold text-slate-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-800 font-semibold">{{ $log->time_in ?? $log->created_at }}</td>
                                    <td class="px-4 py-3.5 font-bold text-slate-900" x-text="duration(@js((string) ($log->time_in ?? $log->created_at)), @js((string) ($log->time_out ?? '')))"></td>
                                    <td class="px-4 py-3.5">
                                        @if($logStatus === 'Checked-In')
                                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-950 border border-emerald-400">Checked-In</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-slate-200 text-slate-900 border border-slate-400">Checked-Out</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        @if($logStatus === 'Checked-In' && isset($log->id))
                                            <form action="{{ route('guard.check.out', $log->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="guard-checkout-btn">
                                                    Check-Out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs font-bold text-slate-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-700 font-bold">No gate logs recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>