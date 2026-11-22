@extends('layouts.app')

@section('content')
<style>
    .zcreate-shell,
    .zcreate-shell * {
        box-sizing: border-box;
    }

    .zcreate-shell {
        position: relative;
        font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
        font-variant-numeric: tabular-nums;
        color: #0f172a;
        background: #0f172a;
        overflow-x: hidden;
    }

    .zcreate-scene-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .zcreate-scene-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.34) 0%, rgba(15, 23, 42, 0.48) 100%);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    .zcreate-card {
        position: relative;
        z-index: 10;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255, 255, 255, 0.72);
        border-top: 4px solid #2563eb;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.16);
    }
</style>

<div class="zcreate-shell relative min-h-[calc(100vh-64px)] py-8 sm:py-10">
    <!-- Welcome-Style Campus Scene Background -->
    <div class="zcreate-scene-bg" aria-hidden="true">
        <svg class="h-full w-full object-cover" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="zcrBgSky" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#3b82f6" />
                    <stop offset="55%" stop-color="#93c5fd" />
                    <stop offset="100%" stop-color="#e0f2fe" />
                </linearGradient>
                <linearGradient id="zcrBgAsphalt" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#475569" />
                    <stop offset="45%" stop-color="#334155" />
                    <stop offset="100%" stop-color="#1e293b" />
                </linearGradient>
                <linearGradient id="zcrBgBldg" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#f8fafc" />
                    <stop offset="100%" stop-color="#cbd5e1" />
                </linearGradient>
            </defs>
            <rect width="1600" height="440" fill="url(#zcrBgSky)" />
            <ellipse cx="420" cy="115" rx="95" ry="26" fill="#ffffff" fill-opacity="0.72" />
            <ellipse cx="1180" cy="95" rx="115" ry="28" fill="#ffffff" fill-opacity="0.68" />
            <rect x="640" y="150" width="960" height="245" fill="url(#zcrBgBldg)" />
            <polygon points="0,425 1600,425 1600,900 0,900" fill="url(#zcrBgAsphalt)" />
            <polygon points="410,565 755,565 750,578 405,578" fill="#eab308" />
            <polygon points="1185,635 1600,635 1600,652 1175,652" fill="#eab308" />
        </svg>
        <div class="zcreate-scene-scrim"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4">
        <div class="zcreate-card p-4 sm:p-8">
            <div class="mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Create Parking Zone') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Add a new parking zone to the campus system.</p>
            </div>

            <form action="{{ route('admin.zones.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Zone Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_capacity" class="block font-medium text-sm text-gray-700">Total Capacity</label>
                    <input type="number" name="total_capacity" id="total_capacity" value="{{ old('total_capacity') }}" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @error('total_capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location_description" class="block font-medium text-sm text-gray-700">Location Description</label>
                    <input type="text" name="location_description" id="location_description" value="{{ old('location_description') }}" placeholder="North Wing, beside Campus Cafeteria" class="mt-1 block w-full rounded-md border-gray-300">
                </div>

                <div>
                    <label for="landmark" class="block font-medium text-sm text-gray-700">Nearest Landmark</label>
                    <input type="text" name="landmark" id="landmark" value="{{ old('landmark') }}" placeholder="Near Main Gate" class="mt-1 block w-full rounded-md border-gray-300">
                </div>

                <div x-data="{ x: {{ old('map_x', 50) }}, y: {{ old('map_y', 50) }} }">
                    <label class="block font-medium text-sm text-gray-700 mb-2">Campus Map Pin Location</label>
                    <input type="hidden" name="map_x" :value="x">
                    <input type="hidden" name="map_y" :value="y">
                    <div class="admin-campus-map" @click="const r=$event.currentTarget.getBoundingClientRect(); x=Math.round((($event.clientX-r.left)/r.width)*100); y=Math.round((($event.clientY-r.top)/r.height)*100)">
                        <span class="campus-map-road road-horizontal"></span><span class="campus-map-road road-vertical"></span>
                        <span class="map-building gate">MAIN GATE</span><span class="map-building cafeteria">CAFETERIA</span><span class="map-building academic">ACADEMIC</span><span class="map-building gym">GYM</span>
                        <span class="map-pin" :style="`left:${x}%; top:${y}%`"><i></i></span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Pin coordinates: <strong x-text="x"></strong>% X / <strong x-text="y"></strong>% Y</p>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.zones.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs uppercase font-semibold hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs uppercase font-semibold hover:bg-indigo-700">Save Zone</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection