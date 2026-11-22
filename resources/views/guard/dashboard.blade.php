<x-app-layout>
    <style>
        @keyframes guardPinBounce {
            0%, 100% { transform: translate(-50%, -100%) scale(1); }
            50% { transform: translate(-50%, -110%) scale(1.08); }
        }

        .guard-atlas-shell,
        .guard-atlas-shell * {
            box-sizing: border-box;
        }

        .guard-atlas-shell {
            position: relative;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            overflow-x: hidden;
        }

        .guard-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .guard-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        div.guard-header-blue {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%) !important;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        div.guard-header-blue h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 900;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .guard-gate-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            background: #ffffff !important;
            color: #1e3a8a !important;
            -webkit-text-fill-color: #1e3a8a !important;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.22);
        }

        .guard-stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
        }

        .guard-quick-type-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 10px;
        }

        .guard-quick-type-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 10px;
            border-radius: 9px;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .guard-quick-type-btn:hover,
        .guard-quick-type-btn.is-active {
            border-color: #1d4ed8;
            background: #1d4ed8;
            color: #ffffff;
        }

        .driver-style-map-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .campus-grid-canvas {
            position: relative;
            width: 100%;
            height: 470px;
            min-height: 470px;
            border-radius: 14px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            background-color: #f8fafc;
            background-image:
                linear-gradient(to right, #e2e8f0 1.5px, transparent 1.5px),
                linear-gradient(to bottom, #e2e8f0 1.5px, transparent 1.5px);
            background-size: 56px 56px;
        }

        .campus-grid-zoom-layer {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .campus-road-label {
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

        .campus-bldg-chip {
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

        .campus-grounds-patch {
            position: absolute;
            z-index: 2;
            border-radius: 8px;
            background: rgba(134, 239, 172, 0.42);
            border: 1.5px solid rgba(74, 222, 128, 0.65);
            pointer-events: none;
        }

        .campus-zone-block {
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

        .campus-zone-block:hover {
            transform: translate(-50%, -50%) scale(1.03);
        }

        .campus-zone-block.is-full {
            background: #dc2626;
            border-color: #991b1b;
        }

        .campus-zone-block.is-active {
            background: #1d4ed8;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px #2563eb, 0 14px 26px rgba(29, 78, 216, 0.35);
            z-index: 8;
        }

        .campus-zone-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .campus-zone-name {
            font-size: 11.5px;
            font-weight: 900;
            line-height: 1.2;
            color: #ffffff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .campus-zone-ratio {
            padding: 2px 7px;
            border-radius: 6px;
            background: rgba(15, 23, 42, 0.32);
            font-size: 10.5px;
            font-weight: 900;
            color: #ffffff;
            flex-shrink: 0;
        }

        .campus-slot-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .campus-slot-pill {
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

        .campus-slot-pill:hover:not(:disabled) {
            background: #ffffff;
            color: #0f172a;
        }

        .campus-slot-pill.is-reserved {
            background: #f59e0b;
            border-color: #fef3c7;
            color: #ffffff;
        }

        .campus-slot-pill.is-occupied {
            background: rgba(15, 23, 42, 0.45);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fecaca;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .campus-slot-pill.is-picked {
            background: #ffffff;
            border-color: #1d4ed8;
            color: #1d4ed8;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .guard-map-pin {
            position: absolute;
            z-index: 12;
            transform: translate(-50%, -100%);
            transition: left 0.4s cubic-bezier(0.22, 1, 0.36, 1), top 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
            animation: guardPinBounce 1.2s ease-in-out infinite;
        }

        .guard-map-pin-chip {
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

        .guard-map-pin-tail {
            width: 3px;
            height: 8px;
            background: #1d4ed8;
            margin: 0 auto;
        }
    </style>

    @php
        $confirmableReservations = collect($reservations ?? $activeReservations ?? []);
        if ($confirmableReservations->isEmpty()) {
            $confirmableReservations = collect($zones ?? [])->flatMap(function ($z) {
                return collect($z->slots ?? [])
                    ->filter(fn ($s) => strtolower((string) ($s->status ?? '')) === 'reserved')
                    ->map(function ($s) use ($z) {
                        $res = $s->activeReservation ?? $s->reservation ?? null;
                        return (object) [
                            'id' => $res->id ?? $s->reservation_id ?? $s->id,
                            'slot_number' => $s->slot_number,
                            'zone_name' => $z->name ?? $z->zone_name ?? 'Zone',
                            'plate_number' => $res->plate_number ?? $s->plate_number ?? '—',
                            'driver_name' => $res->user->name ?? $res->driver->name ?? $res->driver_name ?? $s->reserved_by_name ?? 'Driver',
                            'vehicle_type' => $res->vehicle_type ?? $s->vehicle_type ?? '',
                        ];
                    });
            });
        }

        $zonesPayload = collect($zones ?? [])->values()->map(function ($z, $idx) {
            $presets = [
                ['x' => 27, 'y' => 33],
                ['x' => 74, 'y' => 33],
                ['x' => 27, 'y' => 76],
                ['x' => 74, 'y' => 76],
            ];
            $p = $presets[$idx % count($presets)];

            $rawX = isset($z->map_x) && is_numeric($z->map_x) ? (float) $z->map_x : null;
            $rawY = isset($z->map_y) && is_numeric($z->map_y) ? (float) $z->map_y : null;

            $hasCustomCoords = $rawX !== null && $rawY !== null && !((int) round($rawX) === 50 && (int) round($rawY) === 50);
            $resolvedX = $hasCustomCoords ? $rawX : $p['x'];
            $resolvedY = $hasCustomCoords ? $rawY : $p['y'];

            $slotItems = collect($z->slots ?? [])->map(function ($s) {
                $status = strtolower((string) ($s->status ?? 'available'));
                $activeRes = $s->activeReservation ?? $s->reservation ?? null;
                $reservedBy = $activeRes->user->name
                    ?? $activeRes->driver->name
                    ?? $activeRes->driver_name
                    ?? $s->reserved_by_name
                    ?? null;
                $plate = $activeRes->plate_number ?? $s->plate_number ?? null;

                return [
                    'id' => (string) $s->id,
                    'slot_number' => $s->slot_number,
                    'status' => $status,
                    'reserved_by' => $reservedBy,
                    'plate_number' => $plate ? strtoupper((string) $plate) : null,
                ];
            })->values();

            $capacity = (int) ($z->total_capacity ?? $slotItems->count() ?? 0);
            $available = $slotItems->where('status', 'available')->count();

            return [
                'id' => (string) $z->id,
                'name' => $z->name ?? $z->zone_name ?? ('Zone ' . ($idx + 1)),
                'capacity' => $capacity,
                'available' => $available,
                'x' => $resolvedX,
                'y' => $resolvedY,
                'slots' => $slotItems,
            ];
        })->values();

        $currentGuard = Auth::user();
        $guardName = $currentGuard->name ?? 'Gate Security Guard';
        $guardGate = $currentGuard->assigned_gate ?? 'Gate 1 — Main Campus Gate';

        $totalLogsCount = isset($logs) ? count($logs) : 0;
        $parkedLogsCount = isset($logs) ? collect($logs)->where('status', 'Checked-In')->count() : 0;
        $outLogsCount = isset($logs) ? collect($logs)->where('status', 'Checked-Out')->count() : 0;

        $guardCheckinUrl = \Illuminate\Support\Facades\Route::has('guard.checkin')
            ? route('guard.checkin')
            : (\Illuminate\Support\Facades\Route::has('guard.check.in') ? route('guard.check.in') : url('/guard/entry'));

        $zoneSlotUrl = \Illuminate\Support\Facades\Route::has('guard.zones.slots')
            ? route('guard.zones.slots')
            : (\Illuminate\Support\Facades\Route::has('guard.api.zones-slots') ? route('guard.api.zones-slots') : '');

        $guardLogsUrl = \Illuminate\Support\Facades\Route::has('guard.logs')
            ? route('guard.logs')
            : url('/guard/logs');
    @endphp

    <script>
        window.guardDriverStyleDashboard = function () {
            const base = typeof window.parkingDashboard === 'function' ? window.parkingDashboard() : {};
            return Object.assign({
                zoneId: '',
                slots: [],
                zonesData: @json($zonesPayload),
                plateInput: @js(old('plate_number', '')),
                driverNameInput: @js(old('driver_name', '')),
                vehicleTypeInput: @js(old('vehicle_type', '')),
                selectedZoneId: '',
                selectedSlotId: '',
                selectedSlotLabel: '',
                zoomScale: 1,
                zoomOrigin: '50% 50%',
                pinX: 27,
                pinY: 33,
                showPin: false,

                initGuardMap() {
                    if (typeof base.init === 'function') base.init.call(this);
                    if (this.zonesData.length > 0 && !this.selectedZoneId) {
                        const first = this.zonesData[0];
                        this.selectedZoneId = String(first.id);
                        this.pinX = Number(first.x ?? 27);
                        this.pinY = Number(first.y ?? 33);
                    }
                    const slotSelect = document.getElementById('parking_slot_id');
                    if (slotSelect) {
                        slotSelect.addEventListener('change', (e) => {
                            this.selectedSlotId = String(e.target.value || '');
                        });
                    }
                },

                setVehicleType(typeName) {
                    this.vehicleTypeInput = typeName;
                    const sel = document.getElementById('vehicle_type_select');
                    if (sel) sel.value = typeName;
                },

                async loadZoneSlots(rawId) {
                    const id = rawId ? String(rawId) : '';
                    if (!id) {
                        this.slots = [];
                        return;
                    }
                    const localZone = this.zonesData.find(z => String(z.id) === id);
                    if (localZone && Array.isArray(localZone.slots)) {
                        this.slots = localZone.slots.filter(s => String(s.status || '').toLowerCase() !== 'occupied');
                    }
                    if (typeof base.loadSlots === 'function') {
                        this.zoneId = id;
                        await base.loadSlots.call(this);
                        if (Array.isArray(this.slots) && this.slots.length > 0) return;
                    }
                    const urlEl = this.$el ? this.$el.dataset.zoneSlotUrl : '';
                    if (urlEl) {
                        try {
                            const sep = urlEl.includes('?') ? '&' : '?';
                            const res = await fetch(`${urlEl}${sep}zone_id=${encodeURIComponent(id)}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            });
                            if (res.ok) {
                                const data = await res.json();
                                const list = Array.isArray(data) ? data : (Array.isArray(data.slots) ? data.slots : []);
                                if (list.length > 0) this.slots = list;
                            }
                        } catch (_) {}
                    }
                },

                async selectZoneBlock(zoneObj) {
                    if (!zoneObj) return;
                    const id = String(zoneObj.id);
                    this.zoneId = id;
                    this.selectedZoneId = id;
                    this.selectedSlotId = '';
                    this.selectedSlotLabel = '';

                    const zoneSelect = document.getElementById('parking_zone_id');
                    if (zoneSelect) zoneSelect.value = id;

                    const targetX = Number(zoneObj.x ?? 27);
                    const targetY = Number(zoneObj.y ?? 33);

                    this.pinX = targetX;
                    this.pinY = targetY;
                    this.zoomOrigin = `${targetX}% ${targetY}%`;
                    this.zoomScale = 1.18;
                    this.showPin = true;

                    await this.loadZoneSlots(id);
                },

                async selectSlotPill(zoneObj, slotObj) {
                    if (!zoneObj || !slotObj) return;
                    if (String(slotObj.status || '').toLowerCase() === 'occupied') return;

                    await this.selectZoneBlock(zoneObj);

                    this.selectedSlotId = String(slotObj.id);
                    this.selectedSlotLabel = slotObj.slot_number;

                    const slotSelect = document.getElementById('parking_slot_id');
                    if (slotSelect) {
                        slotSelect.value = String(slotObj.id);
                    }

                    if (slotObj.plate_number) {
                        this.plateInput = String(slotObj.plate_number).toUpperCase();
                    }
                    if (slotObj.reserved_by) {
                        this.driverNameInput = String(slotObj.reserved_by);
                    }
                },

                resetZoomView() {
                    this.zoomScale = 1;
                    this.zoomOrigin = '50% 50%';
                    this.selectedSlotId = '';
                    this.selectedSlotLabel = '';
                    this.showPin = false;
                }
            }, base);
        };
    </script>

    <div
        x-data="guardDriverStyleDashboard()"
        x-init="initGuardMap()"
        data-zone-slot-url="{{ $zoneSlotUrl }}"
        class="guard-atlas-shell min-h-screen py-6"
    >
        <!-- Welcome-Style Campus Scene Background -->
        <div class="guard-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="grdBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="grdBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="grdBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#grdBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#grdBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#grdBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="guard-scene-scrim"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm font-bold shadow-xs">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-sm font-bold shadow-xs">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-sm font-bold shadow-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Top Status Bar -->
            <div class="bg-white border border-slate-300 rounded-2xl px-5 py-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="guard-stat-chip">Guard: <strong>{{ $guardName }}</strong></span>
                    <span class="guard-stat-chip">Gate: <strong>{{ $guardGate }}</strong></span>
                    <span class="guard-stat-chip">Parked: <strong>{{ $parkedLogsCount }}</strong></span>
                    <span class="guard-stat-chip">Checked-Out: <strong>{{ $outLogsCount }}</strong></span>
                    <span class="guard-stat-chip">Logs: <strong>{{ $totalLogsCount }}</strong></span>
                </div>
                <a href="{{ $guardLogsUrl }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold transition">
                    Gate Logs →
                </a>
            </div>

            <!-- Reserved Slots / Confirm Reservation Section (guard.reservations.confirm) -->
            <div class="bg-white border border-slate-300 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">
                        Reserved Slots to Confirm
                    </h3>
                    <span class="text-xs font-extrabold px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                        {{ $confirmableReservations->count() }} Reserved
                    </span>
                </div>

                @if ($confirmableReservations->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($confirmableReservations as $res)
                            @php
                                $resId = data_get($res, 'id');
                                $slotNum = data_get($res, 'slot.slot_number') ?? data_get($res, 'slot_number') ?? 'Slot';
                                $zoneLbl = data_get($res, 'slot.zone.name') ?? data_get($res, 'zone_name') ?? '';
                                $plateLbl = strtoupper((string) (data_get($res, 'plate_number') ?? '—'));
                                $driverLbl = data_get($res, 'user.name') ?? data_get($res, 'driver.name') ?? data_get($res, 'driver_name') ?? '';
                            @endphp
                            <div class="p-3.5 rounded-xl border border-amber-300 bg-amber-50/80 flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-black text-slate-950">
                                        {{ $slotNum }} @if($zoneLbl) · <span class="text-slate-600 font-bold">{{ $zoneLbl }}</span> @endif
                                    </div>
                                    <div class="text-xs font-bold text-slate-700 mt-0.5">
                                        Plate: <span class="uppercase font-black">{{ $plateLbl }}</span>
                                        @if($driverLbl) · {{ $driverLbl }} @endif
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('guard.reservations.confirm', $resId) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold uppercase tracking-wider shadow-xs transition cursor-pointer"
                                    >
                                        Confirm
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs font-semibold text-slate-500">
                        No pending reservations to confirm right now.
                    </p>
                @endif
            </div>

            <!-- Main Grid: Left Gate Check-In + Right Driver-Style Campus Map -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Gate Check-In Card -->
                <div class="lg:col-span-4 bg-white border border-slate-300 rounded-2xl shadow-sm overflow-hidden">
                    <div class="guard-header-blue">
                        <h3>Gate Check-In</h3>
                        <span class="guard-gate-pill">{{ $guardGate }}</span>
                    </div>

                    <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>On Duty: <strong class="text-slate-950">{{ $guardName }}</strong></span>
                        <span class="text-blue-700 font-extrabold">{{ $guardGate }}</span>
                    </div>

                    <form method="POST" action="{{ $guardCheckinUrl }}" class="p-5 space-y-4">
                        @csrf

                        <div>
                            <label for="plate_number" class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-1.5">
                                Plate Number *
                            </label>
                            <input
                                id="plate_number"
                                type="text"
                                name="plate_number"
                                x-model="plateInput"
                                @input="plateInput = plateInput.toUpperCase()"
                                required
                                placeholder="e.g. ABC-1234"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-sm font-bold uppercase text-slate-900"
                            >
                        </div>

                        <div>
                            <label for="driver_name" class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-1.5">
                                Driver Name
                            </label>
                            <input
                                id="driver_name"
                                type="text"
                                name="driver_name"
                                x-model="driverNameInput"
                                placeholder="Walk-in driver name (optional)"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-sm font-semibold text-slate-900"
                            >
                        </div>

                        <div>
                            <label for="vehicle_type_select" class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-1.5">
                                Vehicle Type
                            </label>
                            <div class="guard-quick-type-grid">
                                <button type="button" @click="setVehicleType('Car')" :class="{ 'is-active': vehicleTypeInput === 'Car' }" class="guard-quick-type-btn">🚗 Car</button>
                                <button type="button" @click="setVehicleType('Motorcycle')" :class="{ 'is-active': vehicleTypeInput === 'Motorcycle' }" class="guard-quick-type-btn">🏍️ Motorcycle</button>
                                <button type="button" @click="setVehicleType('SUV / Van')" :class="{ 'is-active': vehicleTypeInput === 'SUV / Van' }" class="guard-quick-type-btn">🚐 SUV / Van</button>
                                <button type="button" @click="setVehicleType('Tricycle')" :class="{ 'is-active': vehicleTypeInput === 'Tricycle' }" class="guard-quick-type-btn">🛺 Tricycle</button>
                            </div>
                            <select
                                id="vehicle_type_select"
                                name="vehicle_type"
                                x-model="vehicleTypeInput"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-sm font-semibold text-slate-900"
                            >
                                <option value="">Select vehicle type...</option>
                                <option value="Car">Car</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="SUV / Van">SUV / Van</option>
                                <option value="Tricycle">Tricycle</option>
                                <option value="Truck / Bus">Truck / Bus</option>
                            </select>
                        </div>

                        <div>
                            <label for="parking_zone_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-1.5">
                                Parking Zone *
                            </label>
                            <select
                                id="parking_zone_id"
                                name="parking_zone_id"
                                x-model="zoneId"
                                @change="selectZoneBlock(zonesData.find(z => String(z.id) === String($event.target.value)))"
                                required
                                class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-sm font-semibold text-slate-900"
                            >
                                <option value="">Select zone...</option>
                                @foreach ($zones ?? [] as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="parking_slot_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-800 mb-1.5">
                                Parking Slot *
                            </label>
                            <select
                                id="parking_slot_id"
                                name="parking_slot_id"
                                x-model="selectedSlotId"
                                required
                                class="w-full rounded-xl border-slate-300 focus:border-blue-600 focus:ring-blue-600 text-sm font-semibold text-slate-900"
                            >
                                <option value="">Select slot...</option>
                                <template x-for="slot in slots" :key="slot.id">
                                    <option
                                        :value="slot.id"
                                        :disabled="slot.status === 'occupied'"
                                        x-text="slot.slot_number + ' — ' + slot.status.toUpperCase()"
                                    ></option>
                                </template>
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-sm font-extrabold uppercase tracking-wider shadow-sm transition"
                        >
                            Check In Vehicle
                        </button>
                    </form>
                </div>

                <!-- Right Column: Driver-Style Campus Parking Map -->
                <div class="lg:col-span-8 driver-style-map-card">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-950">Campus Parking Map</h3>
                            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                Click any Zone block or Slot pill on the campus map to pan, zoom in, and move the location pin.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="resetZoomView()"
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

                    <div class="campus-grid-canvas">
                        <div
                            class="campus-grid-zoom-layer"
                            :style="`transform: scale(${zoomScale}); transform-origin: ${zoomOrigin};`"
                        >
                            <div class="campus-road-label" style="left: 14px; top: 96px; transform: rotate(-90deg);">WEST LANE</div>
                            <div class="campus-road-label" style="left: 38%; top: 12px;">NORTH CAMPUS AVE.</div>
                            <div class="campus-road-label" style="right: 12px; top: 44%;">EAST LANE</div>
                            <div class="campus-road-label" style="left: 40%; bottom: 10px;">SOUTH GATE ROAD</div>

                            <div class="campus-grounds-patch" style="left: 42%; top: 28%; width: 15%; height: 22%;"></div>
                            <div class="campus-grounds-patch" style="left: 42%; bottom: 16%; width: 15%; height: 22%;"></div>

                            <div class="campus-bldg-chip" style="left: 19%; top: 11%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">📚 Library</div>
                            <div class="campus-bldg-chip" style="left: 45%; top: 11%; background: #dbeafe; border: 1px solid #93c5fd; color: #1e3a8a;">🏛️ Admin &amp; Main Hall</div>
                            <div class="campus-bldg-chip" style="right: 14%; top: 11%; background: #e0e7ff; border: 1px solid #a5b4fc; color: #3730a3;">🔬 Science &amp; ICT</div>
                            <div class="campus-bldg-chip" style="left: 19%; top: 53%; background: #fef3c7; border: 1px solid #fcd34d; color: #78350f;">🍽️ Student Center</div>
                            <div class="campus-bldg-chip" style="left: 19%; bottom: 12%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">🏀 Gymnasium</div>
                            <div class="campus-bldg-chip" style="right: 14%; bottom: 12%; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;">🚪 {{ $guardGate }}</div>

                            @foreach ($zonesPayload as $z)
                                <div
                                    @click="selectZoneBlock(@js($z))"
                                    :class="{
                                        'is-active': String(selectedZoneId) === '{{ $z['id'] }}',
                                        'is-full': {{ $z['available'] }} <= 0 && String(selectedZoneId) !== '{{ $z['id'] }}'
                                    }"
                                    class="campus-zone-block"
                                    style="left: {{ $z['x'] }}%; top: {{ $z['y'] }}%;"
                                >
                                    <div class="campus-zone-top">
                                        <span class="campus-zone-name">{{ $z['name'] }}</span>
                                        <span class="campus-zone-ratio">{{ $z['available'] }}/{{ $z['capacity'] }}</span>
                                    </div>
                                    <div class="campus-slot-pills" @click.stop>
                                        @foreach ($z['slots'] as $sl)
                                            <button
                                                type="button"
                                                @click.stop="selectSlotPill(@js($z), @js($sl))"
                                                :disabled="'{{ $sl['status'] }}' === 'occupied'"
                                                :class="{
                                                    'is-reserved': '{{ $sl['status'] }}' === 'reserved',
                                                    'is-occupied': '{{ $sl['status'] }}' === 'occupied',
                                                    'is-picked': String(selectedSlotId) === '{{ $sl['id'] }}'
                                                }"
                                                class="campus-slot-pill"
                                            >{{ $sl['slot_number'] }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div
                                x-show="showPin"
                                :style="`left: ${pinX}%; top: ${pinY}%;`"
                                class="guard-map-pin"
                                style="display: none;"
                            >
                                <div class="guard-map-pin-chip">📍 Selected Zone</div>
                                <div class="guard-map-pin-tail"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>