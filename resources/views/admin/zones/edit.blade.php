<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parking Zone - {{ $zone->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122polished">
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Navigation Header -->
        <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
            <a href="{{ route('admin.zones.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">&larr; Back to Zones</a>
            <span class="text-sm text-gray-500">Edit Zone</span>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Parking Zone</h2>

            <form action="{{ route('admin.zones.update', $zone->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Zone Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $zone->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_capacity" class="block text-sm font-medium text-gray-700">Total Capacity</label>
                    <input type="number" name="total_capacity" id="total_capacity" value="{{ old('total_capacity', $zone->total_capacity ?? $zone->capacity) }}" required min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:border-indigo-500 focus:ring-indigo-500">
                    @error('total_capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location_description" class="block text-sm font-medium text-gray-700">Location Description</label>
                    <input type="text" name="location_description" id="location_description" value="{{ old('location_description', $zone->location_description) }}" placeholder="North Wing, beside Campus Cafeteria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>

                <div>
                    <label for="landmark" class="block text-sm font-medium text-gray-700">Nearest Landmark</label>
                    <input type="text" name="landmark" id="landmark" value="{{ old('landmark', $zone->landmark) }}" placeholder="Near Main Gate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>

                <div x-data="{ x: {{ old('map_x', $zone->map_x ?? 50) }}, y: {{ old('map_y', $zone->map_y ?? 50) }} }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campus Map Pin Location</label>
                    <input type="hidden" name="map_x" :value="x"><input type="hidden" name="map_y" :value="y">
                    <div class="admin-campus-map" @click="const r=$event.currentTarget.getBoundingClientRect(); x=Math.round((($event.clientX-r.left)/r.width)*100); y=Math.round((($event.clientY-r.top)/r.height)*100)">
                        <span class="campus-map-road road-horizontal"></span><span class="campus-map-road road-vertical"></span>
                        <span class="map-building gate">MAIN GATE</span><span class="map-building cafeteria">CAFETERIA</span><span class="map-building academic">ACADEMIC</span><span class="map-building gym">GYM</span>
                        <span class="map-pin" :style="`left:${x}%; top:${y}%`"><i></i></span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Pin coordinates: <strong x-text="x"></strong>% X / <strong x-text="y"></strong>% Y</p>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.zones.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold text-xs uppercase hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase hover:bg-indigo-700">
                        Update Zone
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>