<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Slot - {{ $zone->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/campus-system.css') }}?v=20261122polished">
</head>
<body class="bg-gray-100 min-h-screen py-10" x-data="parkingDashboard()">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
            <a href="{{ route('driver.zones') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">&larr; Back to Zones</a>
            <h2 class="text-xl font-bold text-gray-800">{{ $zone->name }} - Reserve a Slot</h2>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg font-medium shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Slot Interactive Grid -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Click an available slot to reserve:</h3>

            <div class="parking-lot-map">
                <div class="parking-direction">↑ ↑ ↑</div>
                <div class="parking-bay-grid">
                    @foreach($slots as $slot)
                        <div class="parking-bay {{ $slot->status }}">
                            <span class="parking-bay-number">{{ $slot->slot_number }}</span>
                            <svg class="parking-car-svg" viewBox="0 0 80 56" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 22l7-10h30l7 10 8 5v18H10V27l8-5z"/><path d="M25 12l-3 10h36l-3-10M10 35h60M20 45v5M60 45v5"/><circle cx="22" cy="35" r="3" fill="currentColor"/><circle cx="58" cy="35" r="3" fill="currentColor"/></svg>
                            <span class="parking-bay-status">{{ ucfirst($slot->status) }}</span>
                            @if($slot->status === 'available')
                                <button type="button" @click="openReservation({{ $slot->id }})" class="mt-2 px-3 py-1 rounded-md bg-emerald-500 text-white text-[10px] font-bold">Reserve</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Reservation Modal -->
        <div x-show="reservationModal" x-cloak class="smart-modal-backdrop">
            <div class="smart-modal" @click.outside="closeReservation()">
                <button type="button" class="smart-modal-close" @click="closeReservation()">×</button>
                <h3>Reserve Parking Slot</h3>
                <p>Enter your vehicle information.</p>
                <form method="POST" :action="`{{ url('/driver/slots') }}/${reservationSlotId}/reserve`" class="mt-5">
                    @csrf
                    <label class="block text-sm font-bold text-gray-700 mb-2">Plate Number</label>
                    <input type="text" name="plate_number" maxlength="30" required class="w-full mb-4">
                    
                    <label class="block text-sm font-bold text-gray-700 mb-2">Vehicle Type</label>
                    <select name="vehicle_type" required class="w-full mb-5">
                        <option value="Car">🚗 Car</option>
                        <option value="Motorcycle">🏍️ Motorcycle</option>
                        <option value="SUV / Van">🚙 SUV / Van</option>
                        <option value="Bicycle / E-Bike">🚲 Bicycle / E-Bike</option>
                    </select>
                    
                    <button type="submit" class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold">Confirm Reservation</button>
                </form>
            </div>
        </div>

    </div>

</body>
</html>