<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Parking - Select Zone</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122polished">
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h2 class="font-bold text-2xl text-gray-800 mb-4">Available Parking Zones</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($zones as $zone)
                <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $zone->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Capacity: {{ $zone->total_capacity ?? $zone->capacity }} slots</p>
                    </div>
                    <a href="{{ route('driver.slots', $zone->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase hover:bg-indigo-700">
                        View Slots
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>