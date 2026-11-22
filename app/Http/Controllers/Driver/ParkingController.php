<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ParkingController extends Controller
{
    // Driver Dashboard View with Zones & Slots
    public function index(): View
    {
        $this->releaseExpiredReservations();

        $zones = ParkingZone::where('is_active', true)
            ->with(['slots' => function ($query) {
                $query->with(['reservation' => function ($q) {
                    $q->where('status', 'reserved')
                        ->where('expires_at', '>', now())
                        ->with('user');
                }])->orderBy('slot_number');
            }])
            ->orderBy('name')
            ->get();

        $activeReservation = Reservation::with(['slot.zone', 'user'])
            ->where('user_id', auth()->id())
            ->where('status', 'reserved')
            ->where('expires_at', '>', now())
            ->latest('reserved_at')
            ->first();

        return view('driver.dashboard', compact('zones', 'activeReservation'));
    }

    public function showSlots(ParkingZone $zone): View
    {
        $this->releaseExpiredReservations();

        if (! $zone->is_active) {
            abort(404, 'This parking zone is unavailable.');
        }

        $slots = $zone->slots()
            ->with(['reservation' => function ($q) {
                $q->where('status', 'reserved')
                    ->where('expires_at', '>', now())
                    ->with('user');
            }])
            ->orderBy('slot_number')
            ->get();

        return view('driver.slots', compact('zone', 'slots'));
    }

    // Reservation Logic
    public function reserveSlot(Request $request, ParkingSlot $slot): RedirectResponse
    {
        $request->validate([
            'plate_number' => ['required', 'string', 'max:30'],
            'vehicle_type' => ['required', 'in:Car,Motorcycle,SUV / Van,Bicycle / E-Bike'],
        ]);

        $this->releaseExpiredReservations();

        $slot->loadMissing('zone');

        if (! $slot->zone || ! $slot->zone->is_active) {
            return redirect()->back()->with('error', 'This parking zone is temporarily disabled.');
        }

        if ($slot->status !== 'available') {
            return redirect()->back()->with('error', 'This parking slot is not available.');
        }

        $existingReservation = Reservation::where('user_id', auth()->id())
            ->where('status', 'reserved')
            ->where('expires_at', '>', now())
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'You already have an active parking reservation.');
        }

        DB::transaction(function () use ($request, $slot) {
            $slot->update([
                'status' => 'reserved',
                'is_occupied' => false,
            ]);

            Reservation::create([
                'user_id' => auth()->id(),
                'parking_slot_id' => $slot->id,
                'plate_number' => strtoupper(trim($request->plate_number)),
                'vehicle_type' => $request->vehicle_type,
                'status' => 'reserved',
                'reserved_at' => now(),
                'expires_at' => now()->addMinutes(30),
            ]);

            $zone = $slot->zone;
            if ($zone) {
                $zone->update([
                    'available_slots' => max(0, $zone->slots()->where('status', 'available')->count()),
                ]);
            }
        });

        return redirect()->back()->with('success', "Slot {$slot->slot_number} reserved successfully for 30 minutes.");
    }

    public function cancelReservation(Reservation $reservation): RedirectResponse
    {
        if ((int) $reservation->user_id !== (int) auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'cancelled']);

            $slot = $reservation->slot;
            if ($slot && $slot->status === 'reserved') {
                $slot->update([
                    'status' => 'available',
                    'is_occupied' => false,
                ]);

                $zone = $slot->zone;
                if ($zone) {
                    $zone->update([
                        'available_slots' => $zone->slots()->where('status', 'available')->count(),
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Your parking reservation has been cancelled.');
    }

    private function releaseExpiredReservations(): void
    {
        $expired = Reservation::where('status', 'reserved')
            ->where('expires_at', '<=', now())
            ->with('slot.zone')
            ->get();

        foreach ($expired as $reservation) {
            DB::transaction(function () use ($reservation) {
                $reservation->update(['status' => 'cancelled']);

                if ($reservation->slot && $reservation->slot->status === 'reserved') {
                    $slot = $reservation->slot;
                    $slot->update([
                        'status' => 'available',
                        'is_occupied' => false,
                    ]);

                    $zone = $slot->zone;
                    if ($zone) {
                        $zone->update([
                            'available_slots' => $zone->slots()->where('status', 'available')->count(),
                        ]);
                    }
                }
            });
        }
    }
}