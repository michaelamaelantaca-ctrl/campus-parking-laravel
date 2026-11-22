<x-app-layout>
    <style>
        @keyframes vlogMapFlash {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.55), 0 12px 26px rgba(15, 23, 42, 0.14); }
            50% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0.18), 0 16px 32px rgba(15, 23, 42, 0.18); }
            100% { box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08); }
        }

        @keyframes vlogPinBounce {
            0%, 100% { transform: translate(-50%, -100%) scale(1); }
            50% { transform: translate(-50%, -112%) scale(1.09); }
        }

        .vlog-create-shell,
        .vlog-create-shell * {
            box-sizing: border-box;
        }

        .vlog-create-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .vlog-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .vlog-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .vlog-create-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-top: 4px solid #2563eb;
            border-radius: 18px;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
        }

        .vlog-create-label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
            margin-bottom: 6px;
        }

        .vlog-create-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 11px;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        .vlog-create-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.16);
        }

        .vlog-map-shell {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .vlog-map-shell.is-flashing {
            animation: vlogMapFlash 0.9s ease-out;
        }

        .vlog-grid-canvas {
            position: relative;
            width: 100%;
            height: 450px;
            min-height: 450px;
            border-radius: 14px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            background-color: #f8fafc;
            background-image:
                linear-gradient(to right, #e2e8f0 1.5px, transparent 1.5px),
                linear-gradient(to bottom, #e2e8f0 1.5px, transparent 1.5px);
            background-size: 56px 56px;
        }

        .vlog-zoom-layer {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .vlog-road-label {
            position: absolute;
            z-index: 3;
            font-size: 9.5px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            background: rgba(255, 255, 255, 0.88);
            padding: 2px 7px;
            border-radius: 4px;
            pointer-events: none;
        }

        .vlog-grounds-patch {
            position: absolute;
            z-index: 2;
            border-radius: 8px;
            background: rgba(134, 239, 172, 0.42);
            border: 1.5px solid rgba(74, 222, 128, 0.65);
            pointer-events: none;
        }

        .vlog-bldg-chip {
            position: absolute;
            z-index: 4;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
            white-space: nowrap;
            pointer-events: none;
        }

        .vlog-zone-block {
            position: absolute;
            z-index: 6;
            transform: translate(-50%, -50%);
            min-width: 185px;
            max-width: 245px;
            border-radius: 12px;
            padding: 10px 12px;
            color: #ffffff;
            background: #047857;
            border: 2px solid #065f46;
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.22);
            cursor: pointer;
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .vlog-zone-block:hover {
            transform: translate(-50%, -50%) scale(1.03);
        }

        .vlog-zone-block.is-full {
            background: #dc2626;
            border-color: #991b1b;
        }

        .vlog-zone-block.is-active {
            background: #1d4ed8;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px #2563eb, 0 14px 26px rgba(29, 78, 216, 0.35);
            z-index: 8;
        }

        .vlog-zone-block.is-flashing {
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.45), 0 14px 28px rgba(29, 78, 216, 0.45);
        }

        .vlog-slot-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 8px;
        }

        .vlog-slot-pill {
            padding: 2px 6px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 800;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            cursor: pointer;
            transition: all 0.14s ease;
        }

        .vlog-slot-pill:hover:not(:disabled) {
            background: #ffffff;
            color: #0f172a;
        }

        .vlog-slot-pill.is-reserved {
            background: #f59e0b;
            border-color: #fef3c7;
            color: #ffffff;
        }

        .vlog-slot-pill.is-occupied {
            background: rgba(15, 23, 42, 0.45);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fecaca;
            opacity: 0.75;
            cursor: not-allowed;
        }

        .vlog-slot-pill.is-picked {
            background: #ffffff;
            border-color: #1d4ed8;
            color: #1d4ed8;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .vlog-pin {
            position: absolute;
            z-index: 12;
            transform: translate(-50%, -100%);
            transition: left 0.4s cubic-bezier(0.22, 1, 0.36, 1), top 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
            animation: vlogPinBounce 1.1s ease-in-out infinite;
        }

        .vlog-pin-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #1d4ed8;
            color: #ffffff;
            border: 2px solid #ffffff;
            font-size: 10.5px;
            font-weight: 900;
            box-shadow: 0 8px 18px rgba(29, 78, 216, 0.45);
            white-space: nowrap;
        }

        .vlog-pin-tail {
            width: 3px;
            height: 8px;
            background: #1d4ed8;
            margin: 0 auto;
        }
    </style>

    @php
        $rawZones = collect($zones ?? []);
        if ($rawZones->isEmpty() && class_exists(\App\Models\ParkingZone::class)) {
            $rawZones = \App\Models\ParkingZone::query()
                ->when(\Illuminate\Support\Facades\Schema::hasColumn('parking_zones', 'is_active'), fn($q) => $q->where('is_active', true))
                ->get();
        }

        $zonesPayload = $rawZones->values()->map(function ($z, $idx) {
            $presets = [
                ['x' => 27, 'y' => 42],
                ['x' => 53, 'y' => 58],
                ['x' => 27, 'y' => 76],
                ['x' => 76, 'y' => 42],
            ];
            $p = $presets[$idx % count($presets)];

            $rawSlots = collect($z->slots ?? []);
            if ($rawSlots->isEmpty() && isset($z->parkingSlots)) {
                $rawSlots = collect($z->parkingSlots);
            }
            if ($rawSlots->isEmpty() && class_exists(\App\Models\ParkingSlot::class)) {
                $fkCol = \Illuminate\Support\Facades\Schema::hasColumn('parking_slots', 'parking_zone_id')
                    ? 'parking_zone_id'
                    : (\Illuminate\Support\Facades\Schema::hasColumn('parking_slots', 'zone_id') ? 'zone_id' : null);
                if ($fkCol) {
                    $rawSlots = \App\Models\ParkingSlot::where($fkCol, $z->id)->orderBy('id')->get();
                }
            }

            $slotItems = $rawSlots->map(function ($s) use ($z) {
                return [
                    'id' => (string) $s->id,
                    'zone_id' => (string) ($s->parking_zone_id ?? $s->zone_id ?? $z->id),
                    'slot_number' => (string) ($s->slot_number ?? $s->code ?? ('S-' . $s->id)),
                    'status' => strtolower((string) ($s->status ?? 'available')),
                ];
            })->values();

            $capacity = (int) ($z->total_capacity ?? $slotItems->count() ?? 0);
            $available = $slotItems->where('status', 'available')->count();

            return [
                'id' => (string) $z->id,
                'name' => $z->name ?? $z->zone_name ?? ('Zone ' . ($idx + 1)),
                'capacity' => $capacity,
                'available' => $available,
                'x' => isset($z->map_x) && is_numeric($z->map_x) ? (float) $z->map_x : $p['x'],
                'y' => isset($z->map_y) && is_numeric($z->map_y) ? (float) $z->map_y : $p['y'],
                'slots' => $slotItems,
            ];
        })->values();

        $defaultTimeIn = old('time_in', now()->format('Y-m-d\TH:i'));
        $selectedStatus = old('status', 'Checked-In');
        $initialZoneId = (string) old('parking_zone_id', $zonesPayload->first()['id'] ?? '');
        $initialSlotId = (string) old('parking_slot_id', '');

        $logsIndexUrl = \Illuminate\Support\Facades\Route::has('admin.vehicle-logs.index')
            ? route('admin.vehicle-logs.index')
            : url('/admin/vehicle-logs');

        $logsStoreUrl = \Illuminate\Support\Facades\Route::has('admin.vehicle-logs.store')
            ? route('admin.vehicle-logs.store')
            : url('/admin/vehicle-logs');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-700">Parking Operations</p>
                <h2 class="mt-0.5 text-2xl font-black tracking-tight text-slate-950">Create Vehicle Log</h2>
            </div>
            <a
                href="{{ $logsIndexUrl }}"
                class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-xs font-extrabold text-slate-800 shadow-2xs transition hover:bg-slate-50"
            >
                &larr; Back to Vehicle Logs
            </a>
        </div>
    </x-slot>

    <div class="vlog-create-shell min-h-[calc(100vh-64px)] py-3 sm:py-4">
        <!-- Welcome-Style Campus Scene Background -->
        <div class="vlog-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="vlcBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="vlcBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="vlcBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#vlcBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#vlcBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#vlcBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="vlog-scene-scrim"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-rose-300 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-900">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">

                <!-- Left Form -->
                <div class="lg:col-span-5 vlog-create-card overflow-hidden p-6">
                    <form action="{{ $logsStoreUrl }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="plate_number" class="vlog-create-label">Plate Number *</label>
                            <input
                                id="plate_number"
                                type="text"
                                name="plate_number"
                                value="{{ old('plate_number') }}"
                                placeholder="e.g. ABC-1234"
                                oninput="this.value = this.value.toUpperCase()"
                                class="vlog-create-input uppercase"
                                required
                            >
                        </div>

                        <div>
                            <label for="driver_name" class="vlog-create-label">Driver Name</label>
                            <input
                                id="driver_name"
                                type="text"
                                name="driver_name"
                                value="{{ old('driver_name') }}"
                                placeholder="e.g. Juan Dela Cruz"
                                class="vlog-create-input"
                            >
                        </div>

                        <div>
                            <label for="vehicle_type" class="vlog-create-label">Vehicle Type</label>
                            <input
                                id="vehicle_type"
                                type="text"
                                name="vehicle_type"
                                value="{{ old('vehicle_type') }}"
                                list="vehicle_type_suggestions"
                                placeholder="e.g. Motorcycle / Car"
                                class="vlog-create-input"
                            >
                            <datalist id="vehicle_type_suggestions">
                                <option value="Car">
                                <option value="Motorcycle">
                                <option value="SUV / Van">
                                <option value="Tricycle">
                            </datalist>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 rounded-2xl border border-blue-200 bg-blue-50/60 p-4">
                            <div>
                                <label for="parking_zone_id" class="vlog-create-label text-blue-950">Parking Zone</label>
                                <select
                                    id="parking_zone_id"
                                    name="parking_zone_id"
                                    onchange="window.vlogOnZoneSelect(this.value)"
                                    class="vlog-create-input"
                                >
                                    <option value="">Select Zone...</option>
                                    @foreach ($zonesPayload as $z)
                                        <option value="{{ $z['id'] }}" {{ (string) $initialZoneId === (string) $z['id'] ? 'selected' : '' }}>
                                            {{ $z['name'] }} ({{ $z['available'] }}/{{ $z['capacity'] }} free)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="parking_slot_id" class="vlog-create-label text-blue-950">Parking Slot</label>
                                <select
                                    id="parking_slot_id"
                                    name="parking_slot_id"
                                    onchange="window.vlogOnSlotSelect(this.value)"
                                    class="vlog-create-input"
                                >
                                    <option value="">Select Slot...</option>
                                </select>
                                <p id="vlog_slot_hint" class="mt-1 text-[11px] font-bold text-slate-600"></p>
                            </div>
                        </div>

                        <div>
                            <label for="time_in" class="vlog-create-label">Time In *</label>
                            <input
                                id="time_in"
                                type="datetime-local"
                                name="time_in"
                                value="{{ $defaultTimeIn }}"
                                class="vlog-create-input"
                                required
                            >
                        </div>

                        <div>
                            <label for="status" class="vlog-create-label">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="vlog-create-input"
                            >
                                <option value="Checked-In" {{ $selectedStatus === 'Checked-In' ? 'selected' : '' }}>Checked-In</option>
                                <option value="Checked-Out" {{ $selectedStatus === 'Checked-Out' ? 'selected' : '' }}>Checked-Out</option>
                            </select>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
                            <a
                                href="{{ $logsIndexUrl }}"
                                class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-200 px-4 text-sm font-bold text-slate-800 transition hover:bg-slate-300"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-700 px-5 text-sm font-extrabold text-white shadow-xs transition hover:-translate-y-0.5 hover:bg-blue-800 cursor-pointer"
                            >
                                Save Vehicle Log
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Guard-Style Live Map with Flash -->
                <div id="vlog_map_shell" class="lg:col-span-7 vlog-map-shell">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-950">Campus Parking Map</h3>
                            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                Click any Zone block or Slot pill to assign, zoom, and flash the map.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                onclick="window.vlogResetMap()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-xs font-extrabold text-slate-800 transition cursor-pointer"
                            >
                                <span>🔍</span>
                                <span>Reset Zoom (100%)</span>
                            </button>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-emerald-200 bg-emerald-50 text-xs font-extrabold text-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <span>Live Zone Status</span>
                            </span>
                        </div>
                    </div>

                    <div class="vlog-grid-canvas">
                        <div id="vlog_zoom_layer" class="vlog-zoom-layer">
                            <div class="vlog-road-label" style="left: 14px; top: 96px; transform: rotate(-90deg);">WEST LANE</div>
                            <div class="vlog-road-label" style="left: 38%; top: 12px;">NORTH CAMPUS AVE.</div>
                            <div class="vlog-road-label" style="right: 12px; top: 44%;">EAST LANE</div>
                            <div class="vlog-road-label" style="left: 40%; bottom: 10px;">SOUTH GATE ROAD</div>

                            <div class="vlog-grounds-patch" style="left: 42%; top: 28%; width: 15%; height: 22%;"></div>
                            <div class="vlog-grounds-patch" style="left: 42%; bottom: 16%; width: 15%; height: 22%;"></div>

                            <div class="vlog-bldg-chip" style="left: 19%; top: 11%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">📚 Library</div>
                            <div class="vlog-bldg-chip" style="left: 45%; top: 11%; background: #dbeafe; border: 1px solid #93c5fd; color: #1e3a8a;">🏛️ Admin &amp; Main Hall</div>
                            <div class="vlog-bldg-chip" style="right: 14%; top: 11%; background: #e0e7ff; border: 1px solid #a5b4fc; color: #3730a3;">🔬 Science &amp; ICT</div>
                            <div class="vlog-bldg-chip" style="left: 19%; top: 53%; background: #fef3c7; border: 1px solid #fcd34d; color: #78350f;">🍽️ Student Center</div>
                            <div class="vlog-bldg-chip" style="left: 19%; bottom: 12%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">🏀 Gymnasium</div>
                            <div class="vlog-bldg-chip" style="right: 14%; bottom: 12%; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;">🚪 Main Gate</div>

                            @foreach ($zonesPayload as $z)
                                <div
                                    id="vlog_zone_block_{{ $z['id'] }}"
                                    onclick="window.vlogSelectZone('{{ $z['id'] }}')"
                                    class="vlog-zone-block {{ $z['available'] <= 0 ? 'is-full' : '' }}"
                                    style="left: {{ $z['x'] }}%; top: {{ $z['y'] }}%;"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-black truncate">{{ $z['name'] }}</span>
                                        <span class="rounded bg-black/30 px-1.5 py-0.5 text-[10.5px] font-black shrink-0">{{ $z['available'] }}/{{ $z['capacity'] }}</span>
                                    </div>
                                    <div class="vlog-slot-pills">
                                        @foreach ($z['slots'] as $s)
                                            <button
                                                type="button"
                                                id="vlog_slot_pill_{{ $s['id'] }}"
                                                onclick="event.stopPropagation(); window.vlogSelectSlot('{{ $z['id'] }}', '{{ $s['id'] }}');"
                                                @if ($s['status'] === 'occupied') disabled @endif
                                                class="vlog-slot-pill {{ $s['status'] === 'reserved' ? 'is-reserved' : ($s['status'] === 'occupied' ? 'is-occupied' : '') }}"
                                            >
                                                {{ $s['slot_number'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div id="vlog_pin" class="vlog-pin" style="display: none; left: 27%; top: 42%;">
                                <div class="vlog-pin-chip">
                                    <span>📍</span>
                                    <span id="vlog_pin_label">Selected Zone</span>
                                </div>
                                <div class="vlog-pin-tail"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            var zonesData = @json($zonesPayload);
            var initialZoneId = @js($initialZoneId);
            var initialSlotId = @js($initialSlotId);
            var currentZoneId = '';
            var currentSlotId = '';

            function getZone(zid) {
                return zonesData.find(function (z) {
                    return String(z.id) === String(zid);
                }) || null;
            }

            function flashMap(zid) {
                var shell = document.getElementById('vlog_map_shell');
                if (shell) {
                    shell.classList.remove('is-flashing');
                    void shell.offsetWidth;
                    shell.classList.add('is-flashing');
                    setTimeout(function () {
                        shell.classList.remove('is-flashing');
                    }, 900);
                }
                if (zid) {
                    var block = document.getElementById('vlog_zone_block_' + zid);
                    if (block) {
                        block.classList.remove('is-flashing');
                        void block.offsetWidth;
                        block.classList.add('is-flashing');
                        setTimeout(function () {
                            block.classList.remove('is-flashing');
                        }, 900);
                    }
                }
            }

            function updateVisualHighlights(z, slot, showPin) {
                zonesData.forEach(function (item) {
                    var el = document.getElementById('vlog_zone_block_' + item.id);
                    if (el) {
                        if (z && String(item.id) === String(z.id)) {
                            el.classList.add('is-active');
                        } else {
                            el.classList.remove('is-active');
                        }
                    }
                    (item.slots || []).forEach(function (sItem) {
                        var pill = document.getElementById('vlog_slot_pill_' + sItem.id);
                        if (pill) {
                            if (slot && String(sItem.id) === String(slot.id)) {
                                pill.classList.add('is-picked');
                            } else {
                                pill.classList.remove('is-picked');
                            }
                        }
                    });
                });

                var zoomLayer = document.getElementById('vlog_zoom_layer');
                var pinEl = document.getElementById('vlog_pin');
                var pinLabel = document.getElementById('vlog_pin_label');

                if (z) {
                    var px = Number(z.x || 50);
                    var py = Number(z.y || 50);
                    if (zoomLayer) {
                        zoomLayer.style.transformOrigin = px + '% ' + py + '%';
                        zoomLayer.style.transform = showPin ? (slot ? 'scale(1.2)' : 'scale(1.15)') : 'scale(1)';
                    }
                    if (pinEl) {
                        pinEl.style.display = showPin ? 'block' : 'none';
                        pinEl.style.left = px + '%';
                        pinEl.style.top = py + '%';
                    }
                    if (pinLabel) {
                        pinLabel.textContent = slot ? slot.slot_number : z.name;
                    }
                } else {
                    if (zoomLayer) {
                        zoomLayer.style.transformOrigin = '50% 50%';
                        zoomLayer.style.transform = 'scale(1)';
                    }
                    if (pinEl) {
                        pinEl.style.display = 'none';
                    }
                }
            }

            function populateSlotsDropdown(z, preferredSlotId, autoPickFirstAvailable) {
                var slotSelect = document.getElementById('parking_slot_id');
                var hintEl = document.getElementById('vlog_slot_hint');
                if (!slotSelect) return;

                slotSelect.innerHTML = '';
                var defOpt = document.createElement('option');
                defOpt.value = '';
                defOpt.textContent = z ? 'Select Slot...' : 'Select Zone First...';
                slotSelect.appendChild(defOpt);

                if (!z || !Array.isArray(z.slots) || z.slots.length === 0) {
                    currentSlotId = '';
                    if (hintEl) {
                        hintEl.textContent = z ? 'No slots found in this zone.' : '';
                    }
                    return;
                }

                var availCount = 0;
                var firstAvail = null;

                z.slots.forEach(function (s) {
                    var st = String(s.status || 'available').toLowerCase();
                    var isOcc = st === 'occupied';
                    if (!isOcc) {
                        availCount++;
                        if (!firstAvail) firstAvail = s;
                    }
                    var opt = document.createElement('option');
                    opt.value = String(s.id);
                    opt.disabled = isOcc;
                    opt.textContent = s.slot_number + ' — ' + st.toUpperCase();
                    slotSelect.appendChild(opt);
                });

                var chosenId = preferredSlotId ? String(preferredSlotId) : '';
                if (!chosenId && autoPickFirstAvailable && firstAvail) {
                    chosenId = String(firstAvail.id);
                }

                slotSelect.value = chosenId;
                currentSlotId = slotSelect.value || '';

                if (hintEl) {
                    hintEl.textContent = availCount + ' available of ' + z.slots.length + ' slot(s) in ' + z.name;
                }
            }

            window.vlogOnZoneSelect = function (zid) {
                currentZoneId = zid ? String(zid) : '';
                var z = getZone(currentZoneId);
                populateSlotsDropdown(z, '', true);
                var chosenSlot = (z && currentSlotId)
                    ? (z.slots || []).find(function (s) { return String(s.id) === String(currentSlotId); })
                    : null;
                updateVisualHighlights(z, chosenSlot, Boolean(z));
                flashMap(currentZoneId);
            };

            window.vlogOnSlotSelect = function (sid) {
                currentSlotId = sid ? String(sid) : '';
                var z = getZone(currentZoneId);
                var chosenSlot = (z && currentSlotId)
                    ? (z.slots || []).find(function (s) { return String(s.id) === String(currentSlotId); })
                    : null;
                updateVisualHighlights(z, chosenSlot, Boolean(z));
                flashMap(currentZoneId);
            };

            window.vlogSelectZone = function (zid) {
                var zEl = document.getElementById('parking_zone_id');
                if (zEl) zEl.value = String(zid);
                currentZoneId = String(zid);
                var z = getZone(currentZoneId);
                populateSlotsDropdown(z, '', true);
                var chosenSlot = (z && currentSlotId)
                    ? (z.slots || []).find(function (s) { return String(s.id) === String(currentSlotId); })
                    : null;
                updateVisualHighlights(z, chosenSlot, true);
                flashMap(currentZoneId);
            };

            window.vlogSelectSlot = function (zid, sid) {
                var zEl = document.getElementById('parking_zone_id');
                if (zEl) zEl.value = String(zid);
                currentZoneId = String(zid);
                var z = getZone(currentZoneId);
                populateSlotsDropdown(z, String(sid), false);
                var chosenSlot = (z && currentSlotId)
                    ? (z.slots || []).find(function (s) { return String(s.id) === String(currentSlotId); })
                    : null;
                updateVisualHighlights(z, chosenSlot, true);
                flashMap(currentZoneId);
            };

            window.vlogResetMap = function () {
                updateVisualHighlights(null, null, false);
            };

            var initZone = getZone(initialZoneId);
            currentZoneId = initZone ? String(initZone.id) : '';
            populateSlotsDropdown(initZone, initialSlotId, false);
            var initSlot = (initZone && currentSlotId)
                ? (initZone.slots || []).find(function (s) { return String(s.id) === String(currentSlotId); })
                : null;
            updateVisualHighlights(initZone, initSlot, false);
        })();
    </script>
</x-app-layout>