<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-600">Parking Zone</p>
                <h2 class="mt-0.5 text-2xl font-extrabold tracking-tight text-slate-950">{{ $zone->name }} Slots</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.zones.index') }}" class="inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50">← Back to Zones</a>
                <form action="{{ route('admin.zones.slots.generate', $zone->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex h-10 items-center rounded-xl bg-blue-600 px-4 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 cursor-pointer">Generate All Slots</button>
                </form>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes adminSlotPinBounce {
            0%, 100% { transform: translate(-50%, -100%) scale(1); }
            50% { transform: translate(-50%, -110%) scale(1.08); }
        }

        .admin-slots-bg-shell {
            position: relative;
            min-height: 100vh;
            background: #0f172a;
            overflow-x: hidden;
        }

        .admin-slots-scene-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .admin-slots-scene-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .admin-slots-compact-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
        }

        .parking-lot-map {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem;
        }
        .parking-direction {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #475569;
            margin-bottom: 1rem;
        }
        .parking-bay-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 0.875rem;
        }
        .parking-bay {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.875rem;
            border-radius: 0.875rem;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            text-align: center;
        }
        .parking-bay.available {
            border-color: #86efac;
            background: #f0fdf4;
            color: #15803d;
        }
        .parking-bay.reserved {
            border-color: #fcd34d;
            background: #fffbeb;
            color: #b45309;
        }
        .parking-bay.occupied {
            border-color: #fda4af;
            background: #fff1f2;
            color: #be123c;
        }
        .parking-bay-number {
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
        }
        .parking-car-svg {
            width: 56px !important;
            height: 40px !important;
            max-width: 56px !important;
            max-height: 40px !important;
            flex-shrink: 0;
        }
        .parking-bay-status {
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Exact Guard & Driver Style Grid Campus Map */
        .admin-atlas-grid-canvas {
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

        .admin-atlas-zoom-layer {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .admin-atlas-road-label {
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

        .admin-atlas-grounds {
            position: absolute;
            z-index: 2;
            border-radius: 8px;
            background: rgba(134, 239, 172, 0.42);
            border: 1.5px solid rgba(74, 222, 128, 0.65);
            pointer-events: none;
        }

        .admin-atlas-bldg-chip {
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

        .admin-atlas-zone-block {
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

        .admin-atlas-zone-block:hover {
            transform: translate(-50%, -50%) scale(1.03);
        }

        .admin-atlas-zone-block.is-full {
            background: #dc2626;
            border-color: #991b1b;
        }

        .admin-atlas-zone-block.is-active {
            background: #1d4ed8;
            border-color: #ffffff;
            box-shadow: 0 0 0 3px #2563eb, 0 14px 26px rgba(29, 78, 216, 0.35);
            z-index: 8;
        }

        .admin-atlas-zone-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .admin-atlas-zone-name {
            font-size: 11.5px;
            font-weight: 900;
            line-height: 1.2;
            color: #ffffff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-atlas-zone-ratio {
            padding: 2px 7px;
            border-radius: 6px;
            background: rgba(15, 23, 42, 0.32);
            font-size: 10.5px;
            font-weight: 900;
            color: #ffffff;
            flex-shrink: 0;
        }

        .admin-atlas-slot-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .admin-atlas-slot-pill {
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

        .admin-atlas-slot-pill:hover {
            background: #ffffff;
            color: #0f172a;
        }

        .admin-atlas-slot-pill.is-reserved {
            background: #f59e0b;
            border-color: #fef3c7;
            color: #ffffff;
        }

        .admin-atlas-slot-pill.is-occupied {
            background: rgba(15, 23, 42, 0.45);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fecaca;
            opacity: 0.75;
        }

        .admin-atlas-slot-pill.is-picked {
            background: #ffffff;
            border-color: #1d4ed8;
            color: #1d4ed8;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .admin-atlas-pin {
            position: absolute;
            z-index: 12;
            transform: translate(-50%, -100%);
            transition: left 0.4s cubic-bezier(0.22, 1, 0.36, 1), top 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
            animation: adminSlotPinBounce 1.2s ease-in-out infinite;
        }

        .admin-atlas-pin-chip {
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

        .admin-atlas-pin-tail {
            width: 3px;
            height: 8px;
            background: #1d4ed8;
            margin: 0 auto;
        }

        .admin-atlas-legend-strip {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
        }

        .admin-atlas-legend-items {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
        }

        .admin-atlas-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .admin-atlas-legend-swatch {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            display: inline-block;
        }
    </style>

    @php
        $zonePresets = [
            ['x' => 27, 'y' => 33],
            ['x' => 74, 'y' => 33],
            ['x' => 27, 'y' => 74],
            ['x' => 74, 'y' => 74],
        ];
        $presetIdx = abs((int) ($zone->id ?? 0)) % count($zonePresets);
        $fallbackCoords = $zonePresets[$presetIdx];

        $rawX = isset($zone->map_x) && is_numeric($zone->map_x) ? (float) $zone->map_x : null;
        $rawY = isset($zone->map_y) && is_numeric($zone->map_y) ? (float) $zone->map_y : null;
        $isDefaultCenter = $rawX === null || $rawY === null || ((int) round($rawX) === 50 && (int) round($rawY) === 50);

        $mapX = max(24, min(76, $isDefaultCenter ? $fallbackCoords['x'] : $rawX));
        $mapY = max(28, min(74, $isDefaultCenter ? $fallbackCoords['y'] : $rawY));

        $availableCount = collect($slots)->where('status', 'available')->count();
        $totalCap = (int) ($zone->total_capacity ?? $slots->count());
        $slotListForJs = collect($slots)->map(fn($s) => [
            'id' => (string) $s->id,
            'slot_number' => (string) $s->slot_number,
            'status' => strtolower((string) ($s->status ?? 'available')),
        ])->values();
    @endphp

    <div class="admin-slots-bg-shell py-5 sm:py-6">
        <!-- Welcome-Style Campus Scene Background -->
        <div class="admin-slots-scene-bg" aria-hidden="true">
            <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="zslBgSky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" />
                        <stop offset="55%" stop-color="#93c5fd" />
                        <stop offset="100%" stop-color="#e0f2fe" />
                    </linearGradient>
                    <linearGradient id="zslBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="45%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="zslBgBldg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f8fafc" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                </defs>
                <rect width="1600" height="440" fill="url(#zslBgSky)" />
                <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
                <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
                <rect x="640" y="150" width="960" height="245" fill="url(#zslBgBldg)" />
                <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#zslBgAsphalt)" />
                <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
                <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
            </svg>
            <div class="admin-slots-scene-scrim"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="admin-slots-compact-bar">
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-extrabold uppercase tracking-[0.14em] text-blue-700">{{ $zone->name }}</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-sm font-extrabold text-slate-900">Slots Management</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.zones.index') }}" class="inline-flex h-9 items-center rounded-xl border border-slate-200 bg-white px-3.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">← Back to Zones</a>
                    <form action="{{ route('admin.zones.slots.generate', $zone->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-3.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 cursor-pointer">Generate All Slots</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Capacity</p><strong class="mt-0.5 block text-2xl font-extrabold text-slate-950">{{ $zone->total_capacity ?? 0 }}</strong></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wider text-slate-500">Generated Slots</p><strong class="mt-0.5 block text-2xl font-extrabold text-slate-950">{{ $slots->count() }}</strong></div>
                <div class="col-span-2 rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm lg:col-span-1"><p class="text-xs font-bold uppercase tracking-wider text-slate-500">Zone Status</p><strong class="mt-1.5 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ $zone->is_active ? 'Active' : 'Disabled' }}</strong></div>
            </div>

            <!-- Exact Guard & Driver Style Campus Map -->
            <div
                x-data="{
                    zoomScale: 1,
                    zoomOrigin: '{{ $mapX }}% {{ $mapY }}%',
                    pinX: {{ $mapX }},
                    pinY: {{ $mapY }},
                    showPin: false,
                    activeZone: false,
                    pickedSlotId: '',
                    pickedSlotLabel: '',
                    focusAt(x, y, label = '', slotId = '') {
                        const clampedX = Math.max(20, Math.min(80, Number(x || 50)));
                        const clampedY = Math.max(24, Math.min(76, Number(y || 50)));
                        this.pinX = clampedX;
                        this.pinY = clampedY;
                        this.zoomOrigin = `${clampedX}% ${clampedY}%`;
                        this.activeZone = true;
                        this.pickedSlotId = slotId ? String(slotId) : '';
                        this.pickedSlotLabel = label;
                        this.zoomScale = 1.18;
                        this.showPin = true;
                    },
                    toggleZoneFocus() {
                        if (this.activeZone && !this.pickedSlotId) {
                            this.resetZoom();
                            return;
                        }
                        this.focusAt(this.pinX, this.pinY, '', '');
                    },
                    pickSlot(slot, idx = 0) {
                        const offsetX = ((idx % 3) - 1) * 2.5;
                        const offsetY = (Math.floor(idx / 3) % 2 === 0 ? -1 : 1) * 2;
                        this.focusAt(this.pinX + offsetX, this.pinY + offsetY, slot.slot_number, slot.id);
                    },
                    resetZoom() {
                        this.zoomScale = 1;
                        this.zoomOrigin = '{{ $mapX }}% {{ $mapY }}%';
                        this.pinX = {{ $mapX }};
                        this.pinY = {{ $mapY }};
                        this.activeZone = false;
                        this.pickedSlotId = '';
                        this.pickedSlotLabel = '';
                        this.showPin = false;
                    }
                }"
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm"
            >
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
                            @click="resetZoom()"
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

                <div class="admin-atlas-grid-canvas">
                    <div
                        class="admin-atlas-zoom-layer"
                        :style="`transform: scale(${zoomScale}); transform-origin: ${zoomOrigin};`"
                    >
                        <div class="admin-atlas-road-label" style="left: 14px; top: 96px; transform: rotate(-90deg);">WEST LANE</div>
                        <div class="admin-atlas-road-label" style="left: 38%; top: 12px;">NORTH CAMPUS AVE.</div>
                        <div class="admin-atlas-road-label" style="right: 12px; top: 44%;">EAST LANE</div>
                        <div class="admin-atlas-road-label" style="left: 40%; bottom: 10px;">SOUTH GATE ROAD</div>

                        <div class="admin-atlas-grounds" style="left: 42%; top: 28%; width: 15%; height: 22%;"></div>
                        <div class="admin-atlas-grounds" style="left: 42%; bottom: 16%; width: 15%; height: 22%;"></div>

                        <div class="admin-atlas-bldg-chip" style="left: 19%; top: 11%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">📚 Library</div>
                        <div class="admin-atlas-bldg-chip" style="left: 45%; top: 11%; background: #dbeafe; border: 1px solid #93c5fd; color: #1e3a8a;">🏛️ Admin &amp; Main Hall</div>
                        <div class="admin-atlas-bldg-chip" style="right: 14%; top: 11%; background: #e0e7ff; border: 1px solid #a5b4fc; color: #3730a3;">🔬 Science &amp; ICT</div>
                        <div class="admin-atlas-bldg-chip" style="left: 19%; top: 53%; background: #fef3c7; border: 1px solid #fcd34d; color: #78350f;">🍽️ Student Center</div>
                        <div class="admin-atlas-bldg-chip" style="left: 19%; bottom: 12%; background: #f1f5f9; border: 1px solid #cbd5e1; color: #0f172a;">🏀 Gymnasium</div>
                        <div class="admin-atlas-bldg-chip" style="right: 14%; bottom: 12%; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;">🚪 Main Gate</div>

                        <div
                            @click="toggleZoneFocus()"
                            :class="{
                                'is-active': activeZone,
                                'is-full': {{ $availableCount }} <= 0 && !activeZone
                            }"
                            class="admin-atlas-zone-block"
                            style="left: {{ $mapX }}%; top: {{ $mapY }}%;"
                        >
                            <div class="admin-atlas-zone-top">
                                <span class="admin-atlas-zone-name">{{ $zone->name }}</span>
                                <span class="admin-atlas-zone-ratio">{{ $availableCount }}/{{ $totalCap }}</span>
                            </div>
                            <div class="admin-atlas-slot-pills">
                                @foreach($slotListForJs as $idx => $s)
                                    <button
                                        type="button"
                                        @click.stop="pickSlot(@js($s), {{ $idx }})"
                                        :class="{
                                            'is-reserved': '{{ $s['status'] }}' === 'reserved',
                                            'is-occupied': '{{ $s['status'] }}' === 'occupied',
                                            'is-picked': pickedSlotId === '{{ $s['id'] }}'
                                        }"
                                        class="admin-atlas-slot-pill"
                                    >
                                        {{ $s['slot_number'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div
                            x-show="showPin"
                            x-cloak
                            class="admin-atlas-pin"
                            :style="`left: ${pinX}%; top: ${pinY}%;`"
                        >
                            <div class="admin-atlas-pin-chip">
                                <span>📍</span>
                                <span x-text="pickedSlotLabel || @js($zone->name)"></span>
                            </div>
                            <div class="admin-atlas-pin-tail"></div>
                        </div>
                    </div>
                </div>

                <div class="admin-atlas-legend-strip">
                    <div class="admin-atlas-legend-items">
                        <span class="admin-atlas-legend-item">
                            <span class="admin-atlas-legend-swatch" style="background: #047857;"></span>
                            <span>Available Zone</span>
                        </span>
                        <span class="admin-atlas-legend-item">
                            <span class="admin-atlas-legend-swatch" style="background: #dc2626;"></span>
                            <span>Full / Occupied Zone</span>
                        </span>
                        <span class="admin-atlas-legend-item">
                            <span class="admin-atlas-legend-swatch" style="background: #1d4ed8;"></span>
                            <span>Zoomed / Active</span>
                        </span>
                        <span class="admin-atlas-legend-item">
                            <span class="admin-atlas-legend-swatch" style="background: #86efac; border: 1px solid #4ade80;"></span>
                            <span>Campus Grounds</span>
                        </span>
                    </div>
                    <span class="text-slate-500">Tip: Click any Zone block or Slot pill to pan &amp; zoom</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4"><h3 class="font-extrabold text-slate-950">Live Slot Visual Layout</h3><p class="mt-1 text-sm text-slate-500">Available, reserved, and occupied parking bays.</p></div>

                <div class="p-4 sm:p-6">
                    <div class="parking-lot-map">
                        <div class="parking-direction">ENTRY →</div>
                        <div class="parking-bay-grid">
                            @forelse($slots as $slot)
                                <div class="parking-bay {{ $slot->status }}">
                                    <span class="parking-bay-number">{{ $slot->slot_number }}</span>
                                    <svg class="parking-car-svg" width="56" height="40" viewBox="0 0 80 56" fill="none" stroke="currentColor" stroke-width="3">
                                        <path d="M18 22l7-10h30l7 10 8 5v18H10V27l8-5z"/><path d="M25 12l-3 10h36l-3-10M10 35h60M20 45v5M60 45v5"/><circle cx="22" cy="35" r="3" fill="currentColor"/><circle cx="58" cy="35" r="3" fill="currentColor"/>
                                    </svg>
                                    <span class="parking-bay-status">{{ ucfirst($slot->status) }}</span>
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center text-sm font-semibold text-slate-400">No slots generated yet. Generate all slots to build this parking layout.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>