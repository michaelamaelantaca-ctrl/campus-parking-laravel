<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-extrabold text-xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Driver Dashboard') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 mt-0.5">
                    Campus Parking Slot Management · Campus Block Map & Slot Zoom
                </p>
            </div>
            <span class="inline-flex items-center gap-2 self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System Online
            </span>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        @keyframes driverSlotPulse {
            0%, 100% {
                transform: translateY(-3px) scale(1.03);
                box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.24), 0 14px 26px rgba(37, 99, 235, 0.2);
            }
            50% {
                transform: translateY(-5px) scale(1.05);
                box-shadow: 0 0 0 11px rgba(37, 99, 235, 0.1), 0 18px 32px rgba(37, 99, 235, 0.25);
            }
        }
        @keyframes campusPinFloat {
            0%, 100% { transform: translate(-50%, -100%) scale(1); }
            50% { transform: translate(-50%, -112%) scale(1.08); }
        }

        .driver-dashboard-shell,
        .driver-dashboard-shell * {
            box-sizing: border-box;
        }
        .driver-dashboard-shell {
            position: relative;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            background: #0f172a;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .driver-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .driver-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .ops-surface {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow:
                0 1px 2px rgba(15, 23, 42, 0.04),
                0 10px 24px -12px rgba(15, 23, 42, 0.10);
        }
        .ops-zone-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px 22px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .ops-zone-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px -10px rgba(15, 23, 42, 0.08);
        }

        .driver-type-chip {
            padding: 6px 12px;
            border-radius: 9px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: all 0.16s ease;
        }
        .driver-type-chip:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1d4ed8;
            transform: translateY(-1px);
        }
        .driver-type-chip.is-active {
            border-color: #1d4ed8;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 6px 14px -4px rgba(37, 99, 235, 0.45);
        }

        .parking-lot-map {
            position: relative;
            background:
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(180deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                #f1f5f9;
            background-size: 24px 24px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 16px;
            box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.05);
        }
        .parking-direction {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 13px;
            border-radius: 999px;
            background: #0f172a;
            color: #f8fafc;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 0.09em;
            margin-bottom: 14px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
        }
        .parking-bay-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(128px, 1fr));
            gap: 12px;
        }
        .parking-bay {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: 7px;
            padding: 13px 10px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.18s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .parking-bay.available {
            background: linear-gradient(180deg, #f0fdf4 0%, #dcfce7 100%);
            border-color: #34d399;
            color: #064e3b;
        }
        .parking-bay.available:hover {
            transform: translateY(-3px);
            border-color: #10b981;
            box-shadow: 0 10px 20px -8px rgba(16, 185, 129, 0.35);
        }
        .parking-bay.reserved {
            background: linear-gradient(180deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #fbbf24;
            color: #78350f;
        }
        .parking-bay.occupied {
            background: linear-gradient(180deg, #fff1f2 0%, #ffe4e6 100%);
            border-color: #fda4af;
            color: #881337;
            opacity: 0.88;
        }
        .parking-bay.is-highlighted {
            border-color: #2563eb !important;
            background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%) !important;
            color: #1e3a8a !important;
            animation: driverSlotPulse 1.1s ease-in-out 3;
            z-index: 3;
        }
        .parking-bay-number {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.03em;
            padding: 2px 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.78);
        }
        .parking-car-svg {
            width: 44px;
            height: 28px;
        }
        .parking-bay-status {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .smart-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 60;
            background: rgba(15, 23, 42, 0.62);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .smart-modal {
            position: relative;
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 26px;
            box-shadow: 0 28px 60px -12px rgba(15, 23, 42, 0.38);
        }
        .smart-modal h3 {
            margin: 0;
            font-size: 19px;
            font-weight: 900;
            letter-spacing: -0.015em;
            color: #0f172a;
        }
        .smart-modal p {
            margin: 6px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }
        .smart-modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #0f172a;
            font-size: 18px;
            font-weight: 800;
            line-height: 1;
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .smart-modal-close:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .live-sync-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 13px;
            border-radius: 999px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 12px;
            font-weight: 800;
            box-shadow: 0 1px 2px rgba(16, 185, 129, 0.1);
        }
        .live-sync-indicator i {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        .campus-grid-viewport {
            position: relative;
            width: 100%;
            height: 470px;
            border-radius: 14px;
            border: 1.5px solid #94a3b8;
            overflow: hidden;
            background-color: #e5e7eb;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.92) 10px, transparent 10px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.92) 10px, transparent 10px);
            background-size: 96px 88px;
            box-shadow: inset 0 2px 10px rgba(15, 23, 42, 0.08);
        }
        .campus-grid-stage {
            position: relative;
            width: 100%;
            height: 100%;
            transform-origin: center center;
            transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }
        .campus-street-label {
            position: absolute;
            z-index: 2;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #475569;
            background: rgba(255, 255, 255, 0.82);
            padding: 1px 6px;
            border-radius: 4px;
            pointer-events: none;
        }
        .campus-green-lot {
            position: absolute;
            z-index: 1;
            border-radius: 8px;
            background: rgba(134, 239, 172, 0.55);
            border: 1px solid rgba(34, 197, 94, 0.5);
        }
        .campus-bldg-tile {
            position: absolute;
            z-index: 3;
            padding: 6px 10px;
            border-radius: 6px;
            background: #cbd5e1;
            border: 1px solid #94a3b8;
            color: #1e293b;
            font-size: 10.5px;
            font-weight: 800;
            box-shadow: 0 3px 8px rgba(15, 23, 42, 0.1);
            white-space: nowrap;
        }
        .campus-bldg-tile.accent-blue {
            background: #93c5fd;
            border-color: #3b82f6;
            color: #1e3a8a;
        }
        .campus-bldg-tile.accent-amber {
            background: #fcd34d;
            border-color: #f59e0b;
            color: #78350f;
        }
        .campus-bldg-tile.accent-rose {
            background: #fca5a5;
            border-color: #ef4444;
            color: #7f1d1d;
        }

        .campus-parking-block {
            position: absolute;
            z-index: 5;
            transform: translate(-50%, -50%);
            min-width: 170px;
            max-width: 245px;
            padding: 8px 10px;
            border-radius: 8px;
            border: 2px solid #dc2626;
            background: #ef4444;
            color: #ffffff;
            text-align: left;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .campus-parking-block.has-available {
            background: #059669;
            border-color: #047857;
        }
        .campus-parking-block:hover {
            transform: translate(-50%, -50%) scale(1.04);
            z-index: 7;
        }
        .campus-parking-block.is-zoomed {
            background: #1d4ed8;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px #1d4ed8, 0 14px 26px rgba(15, 23, 42, 0.34);
            z-index: 8;
        }
        .campus-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            font-size: 11px;
            font-weight: 900;
            line-height: 1.2;
        }
        .campus-block-count {
            font-size: 10px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 4px;
            background: rgba(15, 23, 42, 0.35);
            color: #ffffff;
            white-space: nowrap;
        }
        .campus-block-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            margin-top: 6px;
            padding-top: 5px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }
        .campus-mini-slot {
            padding: 2px 5px;
            border-radius: 3px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.14s ease;
        }
        .campus-mini-slot:hover {
            background: #ffffff;
            color: #0f172a;
        }
        .campus-mini-slot.is-reserved {
            background: rgba(251, 191, 36, 0.45);
        }
        .campus-mini-slot.is-occupied {
            background: rgba(15, 23, 42, 0.45);
        }
        .campus-mini-slot.is-selected {
            background: #ffffff !important;
            color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: scale(1.12);
        }

        .campus-moving-pin {
            position: absolute;
            z-index: 12;
            transform: translate(-50%, -100%);
            pointer-events: none;
            transition: left 0.55s cubic-bezier(0.22, 1, 0.36, 1), top 0.55s cubic-bezier(0.22, 1, 0.36, 1);
            animation: campusPinFloat 1.25s ease-in-out infinite;
        }
        .campus-moving-pin-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #1e3a8a;
            color: #ffffff;
            border: 2px solid #ffffff;
            font-size: 10.5px;
            font-weight: 900;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.45);
            white-space: nowrap;
        }
        .campus-moving-pin-stem {
            width: 3px;
            height: 9px;
            background: #1e3a8a;
            margin: 0 auto;
        }

        .slot-locator-banner {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 55%, #2563eb 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 16px 32px -12px rgba(29, 78, 216, 0.42);
        }
        .locator-kicker {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 900;
            letter-spacing: 0.09em;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            margin-bottom: 6px;
        }
        .slot-locator-banner strong {
            display: block;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.01em;
            color: #ffffff;
        }
        .slot-locator-banner p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #dbeafe;
        }
        .locator-button {
            padding: 10px 18px;
            border-radius: 10px;
            border: 0;
            background: #ffffff;
            color: #1e3a8a;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.22);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }
        .locator-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(15, 23, 42, 0.28);
        }

        @media (max-width: 640px) {
            .ops-zone-card { padding: 16px; }
            .campus-grid-viewport { height: 400px; }
            .campus-bldg-tile { font-size: 9px; padding: 4px 7px; }
            .campus-parking-block { min-width: 130px; padding: 6px 8px; }
        }
    </style>

    @php
        $presets = [
            ['x' => 23, 'y' => 28],
            ['x' => 77, 'y' => 28],
            ['x' => 23, 'y' => 73],
            ['x' => 77, 'y' => 73],
            ['x' => 50, 'y' => 26],
            ['x' => 50, 'y' => 75],
        ];

        $rawCoords = collect($zones ?? [])->map(fn ($z) => round((float) ($z->map_x ?? 50), 1) . ',' . round((float) ($z->map_y ?? 50), 1));
        $hasDuplicateCoords = $rawCoords->unique()->count() < $rawCoords->count();

        $zonesMapPayload = collect($zones ?? [])->values()->map(function ($z, $idx) use ($presets, $hasDuplicateCoords) {
            $p = $presets[$idx % count($presets)];
            $dbX = (float) ($z->map_x ?? 0);
            $dbY = (float) ($z->map_y ?? 0);

            $usePreset = $hasDuplicateCoords || ($dbX <= 0 && $dbY <= 0) || (abs($dbX - 50) < 1 && abs($dbY - 50) < 1);
            $x = $usePreset ? $p['x'] : max(14, min(86, $dbX));
            $y = $usePreset ? $p['y'] : max(18, min(82, $dbY));

            $slotsList = $z->slots->values()->map(function ($s, $sIdx) use ($x, $y) {
                $col = ($sIdx % 5) - 2;
                $row = (int) floor($sIdx / 5) - 1;
                return [
                    'id'          => (int) $s->id,
                    'slot_number' => $s->slot_number,
                    'status'      => strtolower($s->status ?? 'available'),
                    'pin_x'       => max(8, min(92, round($x + ($col * 2.2), 1))),
                    'pin_y'       => max(14, min(86, round($y + ($row * 2.6), 1))),
                ];
            })->values();

            return [
                'id'        => (int) $z->id,
                'name'      => $z->name ?? ('Zone ' . ($idx + 1)),
                'x'         => $x,
                'y'         => $y,
                'available' => (int) $z->slots->where('status', 'available')->count(),
                'total'     => (int) $z->slots->count(),
                'slots'     => $slotsList,
            ];
        })->values();

        $slotLookup = collect($zonesMapPayload)->flatMap(function ($z) {
            return collect($z['slots'])->map(function ($s) use ($z) {
                return [
                    'id'          => $s['id'],
                    'slot_number' => $s['slot_number'],
                    'status'      => $s['status'],
                    'zone_id'     => $z['id'],
                    'zone_name'   => $z['name'],
                    'pin_x'       => $s['pin_x'],
                    'pin_y'       => $s['pin_y'],
                ];
            });
        })->keyBy(function ($item) {
            return (string) $item['id'];
        });
    @endphp

    <script>
        window.driverParkingDashboard = function () {
            const externalBase = typeof window.parkingDashboard === 'function' ? window.parkingDashboard() : {};

            return Object.assign({
                reservationModal: false,
                reservationSlotId: null,
                reservationSlotNumber: '',
                reservationZoneName: '',
                plateNumber: '',
                vehicleType: 'Car',
                slotFilter: 'all',
                selectedZoneFilter: 'all',
                activeZonePinId: null,
                highlightedSlotId: null,
                selectedMapSlotId: null,

                zoomScale: 1,
                panX: 0,
                panY: 0,
                pinX: 50,
                pinY: 50,
                pinLabel: 'Campus Map',
                showPin: false,

                mapZones: @json($zonesMapPayload),
                slotLookup: @json($slotLookup),

                initDriverDashboard() {
                    if (typeof externalBase.init === 'function') {
                        externalBase.init.call(this);
                    }
                    if (this.mapZones.length > 0) {
                        const first = this.mapZones[0];
                        this.pinX = Number(first.x);
                        this.pinY = Number(first.y);
                        this.pinLabel = first.name;
                        this.showPin = true;
                    }
                },

                setCameraFocus(xPercent, yPercent, scale) {
                    this.zoomScale = scale;
                    this.panX = Number(((50 - xPercent) * scale).toFixed(2));
                    this.panY = Number(((50 - yPercent) * scale).toFixed(2));
                },

                zoomToZone(zoneId, shouldScroll = false) {
                    const z = this.mapZones.find(item => Number(item.id) === Number(zoneId));
                    if (!z) return;

                    this.activeZonePinId = Number(z.id);
                    this.selectedZoneFilter = String(z.id);
                    this.selectedMapSlotId = null;

                    const x = Number(z.x);
                    const y = Number(z.y);
                    this.setCameraFocus(x, y, 1.45);
                    this.pinX = x;
                    this.pinY = y;
                    this.pinLabel = z.name;
                    this.showPin = true;

                    if (shouldScroll) {
                        const el = document.getElementById(`parking-zone-${z.id}`);
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },

                zoomToSlot(slotId, zoneId = null, shouldScroll = false) {
                    const info = this.slotLookup[String(slotId)] || null;
                    const z = this.mapZones.find(item => Number(item.id) === Number(zoneId || (info && info.zone_id)));

                    const x = info ? Number(info.pin_x) : (z ? Number(z.x) : 50);
                    const y = info ? Number(info.pin_y) : (z ? Number(z.y) : 50);

                    if (z) {
                        this.activeZonePinId = Number(z.id);
                        this.selectedZoneFilter = String(z.id);
                    }

                    this.selectedMapSlotId = Number(slotId);
                    this.highlightedSlotId = Number(slotId);
                    this.setCameraFocus(x, y, 1.85);
                    this.pinX = x;
                    this.pinY = y;
                    this.pinLabel = info ? `${info.slot_number} · ${info.zone_name}` : `Slot #${slotId}`;
                    this.showPin = true;

                    if (shouldScroll) {
                        this.$nextTick(() => {
                            const slotEl = document.getElementById(`parking-slot-${slotId}`);
                            if (slotEl) {
                                slotEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    }

                    setTimeout(() => {
                        if (Number(this.highlightedSlotId) === Number(slotId)) {
                            this.highlightedSlotId = null;
                        }
                    }, 3600);
                },

                resetCampusZoom() {
                    this.zoomScale = 1;
                    this.panX = 0;
                    this.panY = 0;
                    this.selectedMapSlotId = null;
                    this.selectedZoneFilter = 'all';
                    this.slotFilter = 'all';
                    if (this.mapZones.length > 0) {
                        const first = this.mapZones[0];
                        this.pinX = Number(first.x);
                        this.pinY = Number(first.y);
                        this.pinLabel = first.name;
                    }
                },

                openReservation(slotId) {
                    const key = String(slotId);
                    const info = this.slotLookup[key] || null;
                    this.reservationSlotId = slotId;
                    this.reservationSlotNumber = info ? info.slot_number : ('Slot #' + slotId);
                    this.reservationZoneName = info ? info.zone_name : 'Campus Parking';
                    this.reservationModal = true;

                    if (typeof externalBase.openReservation === 'function') {
                        try { externalBase.openReservation.call(this, slotId); } catch (_) {}
                    }
                },

                closeReservation() {
                    this.reservationModal = false;
                    if (typeof externalBase.closeReservation === 'function') {
                        try { externalBase.closeReservation.call(this); } catch (_) {}
                    }
                },

                setVehicleType(type) {
                    this.vehicleType = type;
                },

                selectZone(zoneId) {
                    this.zoomToZone(zoneId, true);
                    if (typeof externalBase.selectZone === 'function') {
                        try { externalBase.selectZone.call(this, zoneId); } catch (_) {}
                    }
                },

                locateSlot(slotId, zoneId = null) {
                    this.slotFilter = 'all';
                    this.zoomToSlot(slotId, zoneId, true);
                    if (typeof externalBase.locateSlot === 'function') {
                        try { externalBase.locateSlot.call(this, slotId, zoneId); } catch (_) {}
                    }
                },

                shouldShowSlot(status) {
                    if (this.slotFilter === 'all') return true;
                    return String(status || '').toLowerCase() === this.slotFilter;
                },

                shouldShowZone(zoneId) {
                    if (this.selectedZoneFilter === 'all') return true;
                    return String(this.selectedZoneFilter) === String(zoneId);
                }
            }, externalBase);
        };
    </script>

    <div class="driver-dashboard-shell py-10" x-data="window.driverParkingDashboard()" x-init="initDriverDashboard()">

        <!-- Welcome-Style Campus Scene Background -->
        <div class="driver-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="drvBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="drvBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="drvBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>

                <rect width="1600" height="440" fill="url(#drvBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="475" cy="108" rx="68" ry="22" fill="#ffffff" fill-opacity="0.78" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />

                <rect x="640" y="150" width="960" height="245" fill="url(#drvBgBldg)" />
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

                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#drvBgAsphalt)" />
                <polygon points="435,515 780,515 755,565 410,565" fill="#166534" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />

                <rect x="742" y="510" width="34" height="86" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
                <rect x="1142" y="535" width="38" height="92" rx="4" fill="#eab308" stroke="#0f172a" stroke-width="2" />
                <polygon points="774,548 1144,572 1144,585 774,561" fill="#ffffff" stroke="#0f172a" stroke-width="1.5" />

                <rect x="1175" y="330" width="255" height="225" fill="#f8fafc" stroke="#94a3b8" stroke-width="2" />
                <polygon points="1145,332 1458,332 1435,272 1170,272" fill="#1d4ed8" />
                <rect x="1175" y="286" width="255" height="44" rx="4" fill="#1e40af" stroke="#ffffff" stroke-width="1.5" />
                <text x="1302" y="305" text-anchor="middle" fill="#ffffff" font-family="Inter, sans-serif" font-size="15" font-weight="900" letter-spacing="2">CAMPUS</text>
                <text x="1302" y="322" text-anchor="middle" fill="#93c5fd" font-family="Inter, sans-serif" font-size="13" font-weight="800" letter-spacing="2.5">PARKING</text>
            </svg>
            <div class="driver-scene-scrim"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-900 rounded-xl font-bold shadow-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-300 text-rose-900 rounded-xl font-bold shadow-xs">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($activeReservation) && $activeReservation)
                <div class="slot-locator-banner">
                    <div>
                        <span class="locator-kicker">YOUR RESERVED SLOT</span>
                        <strong>Slot {{ $activeReservation->slot->slot_number }} — {{ $activeReservation->slot->zone->name }}</strong>
                        <p>{{ $activeReservation->slot->row_label ?? 'Parking lane' }}, {{ $activeReservation->slot->position_order }}{{ $activeReservation->slot->position_order == 1 ? 'st' : ($activeReservation->slot->position_order == 2 ? 'nd' : ($activeReservation->slot->position_order == 3 ? 'rd' : 'th')) }} slot from the entrance.</p>
                    </div>
                    <button type="button" @click="locateSlot({{ $activeReservation->slot->id }}, {{ $activeReservation->slot->zone->id }})" class="locator-button">
                        📍 Locate Slot
                    </button>
                </div>
            @endif

            <!-- Campus Street-Block Map with Zone & Slot Zoom -->
            <div class="campus-overview-card ops-surface p-6 sm:p-7">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Campus Parking Map</h3>
                        <p class="text-sm font-medium text-slate-600 mt-0.5">
                            Click any Zone block or Slot code on the campus map to pan, zoom in, and move the location pin.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="resetCampusZoom()"
                            class="px-3 py-1.5 rounded-lg text-xs font-extrabold border border-slate-300 bg-white text-slate-800 hover:bg-slate-50 transition cursor-pointer"
                        >
                            🔍 Reset Zoom (<span x-text="Math.round(zoomScale * 100) + '%'"></span>)
                        </button>
                        <span class="live-sync-indicator"><i></i> Live Zone Status</span>
                    </div>
                </div>

                <div class="campus-grid-viewport">
                    <div class="campus-grid-stage" :style="`transform: translate(${panX}%, ${panY}%) scale(${zoomScale});`">
                        <div class="campus-green-lot" style="left: 42%; top: 15%; width: 16%; height: 22%;"></div>
                        <div class="campus-green-lot" style="left: 42%; top: 63%; width: 16%; height: 22%;"></div>

                        <span class="campus-street-label" style="top: 3.5%; left: 36%;">NORTH CAMPUS AVE.</span>
                        <span class="campus-street-label" style="top: 48%; left: 44%;">CENTRAL QUAD DRIVE</span>
                        <span class="campus-street-label" style="bottom: 3.5%; left: 38%;">SOUTH GATE ROAD</span>
                        <span class="campus-street-label" style="top: 22%; left: 1.5%; transform: rotate(-90deg); transform-origin: left top;">WEST LANE</span>
                        <span class="campus-street-label" style="top: 22%; right: 1.5%; transform: rotate(90deg); transform-origin: right top;">EAST LANE</span>

                        <div class="campus-bldg-tile" style="left: 14%; top: 8%;">Library</div>
                        <div class="campus-bldg-tile accent-blue" style="left: 44%; top: 9%;">Admin & Main Hall</div>
                        <div class="campus-bldg-tile" style="left: 74%; top: 9%;">Science & ICT</div>
                        <div class="campus-bldg-tile accent-amber" style="left: 14%; top: 86%;">Student Center</div>
                        <div class="campus-bldg-tile" style="left: 73%; top: 86%;">Campus Gate</div>

                        <template x-for="zone in mapZones" :key="zone.id">
                            <div
                                :style="`left: ${zone.x}%; top: ${zone.y}%;`"
                                :class="{
                                    'has-available': Number(zone.available) > 0,
                                    'is-zoomed': Number(activeZonePinId) === Number(zone.id)
                                }"
                                class="campus-parking-block"
                                @click="zoomToZone(zone.id, false)"
                            >
                                <div class="campus-block-header">
                                    <span x-text="zone.name"></span>
                                    <span class="campus-block-count" x-text="`${zone.available}/${zone.total}`"></span>
                                </div>

                                <div class="campus-block-slots" @click.stop>
                                    <template x-for="slot in zone.slots" :key="slot.id">
                                        <button
                                            type="button"
                                            :class="{
                                                'is-reserved': slot.status === 'reserved',
                                                'is-occupied': slot.status === 'occupied',
                                                'is-selected': Number(selectedMapSlotId) === Number(slot.id)
                                            }"
                                            class="campus-mini-slot"
                                            @click.stop="zoomToSlot(slot.id, zone.id, false)"
                                            x-text="slot.slot_number"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div
                            x-show="showPin"
                            :style="`left: ${pinX}%; top: ${pinY}%;`"
                            class="campus-moving-pin"
                        >
                            <div class="campus-moving-pin-pill">
                                <span>📍</span>
                                <span x-text="pinLabel"></span>
                            </div>
                            <div class="campus-moving-pin-stem"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Select an Available Slot to Reserve -->
            <div class="ops-surface p-6 sm:p-7 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Select an Available Slot to Reserve:</h3>
                        <p class="text-sm font-medium text-slate-600 mt-0.5">
                            Click <strong class="text-emerald-700">Reserve</strong> on any available bay or click a slot to highlight it on the campus map.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="slotFilter = 'all'" :class="{ 'is-active': slotFilter === 'all' }" class="driver-type-chip">All</button>
                        <button type="button" @click="slotFilter = 'available'" :class="{ 'is-active': slotFilter === 'available' }" class="driver-type-chip">Available</button>
                        <button type="button" @click="slotFilter = 'reserved'" :class="{ 'is-active': slotFilter === 'reserved' }" class="driver-type-chip">Reserved</button>
                        <button type="button" @click="slotFilter = 'occupied'" :class="{ 'is-active': slotFilter === 'occupied' }" class="driver-type-chip">Occupied</button>
                    </div>
                </div>

                @foreach($zones ?? [] as $zone)
                    <div id="parking-zone-{{ $zone->id }}" x-show="shouldShowZone({{ $zone->id }})" class="ops-zone-card space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h4 class="text-base font-extrabold text-slate-900">{{ $zone->name }}</h4>
                            <button type="button" @click="zoomToZone({{ $zone->id }}, false)" class="driver-type-chip">📍 Zoom Zone</button>
                        </div>

                        <div class="parking-lot-map">
                            <div class="parking-direction">↑ ↑ ↑ ENTRY FLOW</div>
                            <div class="parking-bay-grid">
                                @foreach($zone->slots as $slot)
                                    @php $st = strtolower($slot->status ?? 'available'); @endphp
                                    <div
                                        id="parking-slot-{{ $slot->id }}"
                                        x-show="shouldShowSlot('{{ $st }}')"
                                        @click="zoomToSlot({{ $slot->id }}, {{ $zone->id }}, false)"
                                        :class="{ 'is-highlighted': Number(highlightedSlotId) === {{ $slot->id }} || Number(selectedMapSlotId) === {{ $slot->id }} }"
                                        class="parking-bay {{ $st }} cursor-pointer"
                                    >
                                        <span class="parking-bay-number">{{ $slot->slot_number }}</span>
                                        <svg class="parking-car-svg" viewBox="0 0 48 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <rect x="5" y="9" width="38" height="12" rx="4" fill="currentColor" fill-opacity="0.22" stroke="currentColor" stroke-width="2" />
                                            <path d="M12 9L16 4H32L36 9" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                            <circle cx="14" cy="22" r="3" fill="currentColor" />
                                            <circle cx="34" cy="22" r="3" fill="currentColor" />
                                        </svg>
                                        <span class="parking-bay-status">{{ strtoupper($st) }}</span>
                                        @if($st === 'available')
                                            <button
                                                type="button"
                                                @click.stop="openReservation({{ $slot->id }})"
                                                class="w-full py-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold tracking-wide shadow-xs transition cursor-pointer"
                                            >
                                                Reserve
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reservation Modal (Connected to driver.slots.reserve: /driver/slots/{slot}/reserve) -->
            <div x-show="reservationModal" x-cloak @click.self="closeReservation()" class="smart-modal-backdrop" style="display: none;">
                <div class="smart-modal" @click.stop>
                    <button type="button" class="smart-modal-close" @click="closeReservation()">&times;</button>
                    <h3>Reserve Parking Slot</h3>
                    <p>Slot <strong class="text-slate-900" x-text="reservationSlotNumber"></strong> · <span x-text="reservationZoneName"></span></p>

                    <form
                        method="POST"
                        :action="reservationSlotId ? `/driver/slots/${reservationSlotId}/reserve` : '#'"
                        class="mt-5 space-y-4"
                    >
                        @csrf
                        <input type="hidden" name="slot_id" :value="reservationSlotId">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1">Plate Number</label>
                            <input type="text" name="plate_number" x-model="plateNumber" required placeholder="e.g. ABC 1234" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-bold text-slate-900 uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Vehicle Type</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="setVehicleType('Car')" :class="{ 'is-active': vehicleType === 'Car' }" class="driver-type-chip">Car</button>
                                <button type="button" @click="setVehicleType('Motorcycle')" :class="{ 'is-active': vehicleType === 'Motorcycle' }" class="driver-type-chip">Motorcycle</button>
                                <button type="button" @click="setVehicleType('SUV / Van')" :class="{ 'is-active': vehicleType === 'SUV / Van' }" class="driver-type-chip">SUV / Van</button>
                            </div>
                            <input type="hidden" name="vehicle_type" :value="vehicleType">
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="closeReservation()" class="px-4 py-2 rounded-lg border border-slate-300 bg-white text-xs font-extrabold text-slate-700 cursor-pointer">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-xs font-extrabold text-white cursor-pointer">Confirm Reservation</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>