<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900">Live Parking Slot Availability</h3>
                <p class="text-sm text-gray-600 mb-6">
                    View live available, occupied, and reserved parking slot capacities across campus zones prior to entry.
                </p>

                @forelse($zones as $zone)
                    @php
                        $totalSlots = $zone->slots->count();
                        $availableCount = $zone->slots->where('status', 'available')->count();
                        $occupiedCount = $zone->slots->where('status', 'occupied')->count();
                        $occupancyRate = $totalSlots > 0 ? round(($occupiedCount / $totalSlots) * 100) : 0;
                    @endphp

                    <div class="bg-gray-50 border rounded-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">{{ $zone->name }}</h4>
                                <span class="inline-block mt-1 px-3 py-1 bg-emerald-100 text-emerald-800 font-semibold text-xs rounded-full">
                                    Available
                                </span>
                                <p class="text-xs text-gray-500 mt-2">{{ $occupancyRate }}% Occupied</p>
                            </div>
                            <div class="text-center">
                                <span class="text-4xl font-extrabold text-gray-900 block">{{ $availableCount }}</span>
                                <span class="text-sm font-medium text-gray-600">Slots Available out of {{ $totalSlots }}</span>
                            </div>
                        </div>

                        <!-- Slot Grid Section -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h5 class="text-sm font-bold text-gray-700 mb-3">Select an Available Slot to Reserve:</h5>

                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-10 gap-3">
                                @forelse($zone->slots as $slot)
                                    <div class="p-3 border rounded-lg text-center font-bold text-xs shadow-sm flex flex-col justify-between transition
                                        {{ $slot->status === 'available' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100' : '' }}
                                        {{ $slot->status === 'reserved' ? 'bg-amber-50 border-amber-300 text-amber-800' : '' }}
                                        {{ $slot->status === 'occupied' ? 'bg-red-50 border-red-300 text-red-800' : '' }}">
                                        
                                        <div>
                                            <div class="text-sm font-extrabold">{{ $slot->slot_number }}</div>
                                            <div class="text-[10px] uppercase mt-1 tracking-wider opacity-80">{{ ucfirst($slot->status) }}</div>
                                        </div>

                                        @if($slot->status === 'available')
                                            <form action="{{ route('driver.slots.reserve', $slot->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit" class="w-full py-1 bg-emerald-600 text-white text-[10px] rounded hover:bg-emerald-700 font-bold transition">
                                                    Reserve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-span-full text-sm text-gray-500 italic">
                                        No slots generated for this zone yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No parking zones available right now.</p>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>