<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\VehicleLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GateLogController extends Controller
{
    // 1. Ipakita ang Dashboard sa Guard kasama ang logs, zones, slots, at active reservations
    public function index(): View
    {
        $logs = VehicleLog::with(['zone', 'slot', 'securityGuard'])->latest()->take(100)->get();

        $zones = ParkingZone::where('is_active', true)
            ->with(['slots' => function ($query) {
                $query->whereIn('status', ['available', 'reserved', 'occupied'])
                    ->with(['reservation' => function ($q) {
                        $q->where('status', 'reserved')
                            ->where('expires_at', '>', now())
                            ->with('user');
                    }])
                    ->orderBy('slot_number');
            }])
            ->orderBy('name')
            ->get();

        $reservations = Reservation::with(['slot.zone', 'user'])
            ->where('status', 'reserved')
            ->where('expires_at', '>', now())
            ->latest('reserved_at')
            ->get();

        return view('guard.dashboard', compact('logs', 'zones', 'reservations'));
    }

    // 1b. Hiwalay na page para sa Real-Time Gate Logs
    public function logs(): View
    {
        $logs = VehicleLog::with(['zone', 'slot', 'securityGuard'])->latest()->take(200)->get();

        $reservations = Reservation::with(['slot.zone', 'user'])
            ->where('status', 'reserved')
            ->where('expires_at', '>', now())
            ->latest('reserved_at')
            ->get();

        return view('admin.guard.logs', compact('logs', 'reservations'));
    }

    // 2. API Endpoint para makuha ang Zones at Slots nito para sa JS
    public function getZonesAndSlots()
    {
        $zones = ParkingZone::where('is_active', true)
            ->with(['slots' => function ($query) {
                $query->whereIn('status', ['available', 'reserved', 'occupied'])
                    ->with(['reservation' => function ($q) {
                        $q->where('status', 'reserved')
                            ->where('expires_at', '>', now())
                            ->with('user');
                    }])
                    ->orderBy('slot_number');
            }])
            ->orderBy('name')
            ->get();

        return response()->json($zones);
    }

    // 3. I-process ang pag Check-In sa Sakyanan
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'plate_number' => ['required', 'string', 'max:30'],
            'driver_name' => ['nullable', 'string', 'max:255'],
            'vehicle_type' => ['nullable', 'string', 'max:100'],
            'parking_zone_id' => ['required', 'exists:parking_zones,id'],
            'parking_slot_id' => ['required', 'exists:parking_slots,id'],
            'time_in' => ['nullable', 'date'],
        ]);

        $slot = ParkingSlot::with(['zone', 'reservation'])->findOrFail($request->parking_slot_id);

        if ((int) $slot->parking_zone_id !== (int) $request->parking_zone_id) {
            return back()->with('error', 'The selected slot does not belong to the selected parking zone.')->withInput();
        }

        if (! $slot->zone->is_active) {
            return back()->with('error', 'The selected parking zone is temporarily disabled.')->withInput();
        }

        if (! in_array($slot->status, ['available', 'reserved'], true)) {
            return back()->with('error', 'The selected parking slot is no longer available.')->withInput();
        }

        $activeLog = VehicleLog::where('plate_number', strtoupper(trim($request->plate_number)))
            ->where('status', 'Checked-In')
            ->exists();

        if ($activeLog) {
            return back()->with('error', 'This vehicle already has an active check-in.')->withInput();
        }

        DB::transaction(function () use ($request, $slot) {
            VehicleLog::create([
                'plate_number' => strtoupper(trim($request->plate_number)),
                'driver_name' => $request->driver_name,
                'vehicle_type' => $request->vehicle_type,
                'parking_zone_id' => $slot->parking_zone_id,
                'parking_slot_id' => $slot->id,
                'guard_id' => Auth::id(),
                'time_in' => $request->time_in ?? now(),
                'status' => 'Checked-In',
            ]);

            if ($slot->status === 'reserved') {
                Reservation::where('parking_slot_id', $slot->id)
                    ->where('status', 'reserved')
                    ->update(['status' => 'occupied']);
            }

            $slot->update([
                'status' => 'occupied',
                'is_occupied' => true,
            ]);

            $zone = $slot->zone;
            $zone->update([
                'available_slots' => max(0, $zone->slots()->where('status', 'available')->count()),
            ]);
        });

        return redirect()->back()->with('success', 'Vehicle checked in successfully.');
    }

    public function checkIn(Request $request): RedirectResponse
    {
        return $this->store($request);
    }

    // 4. I-process ang pag Check-Out (Ibabalik sa available ang slot)
    public function update(Request $request, VehicleLog $log): RedirectResponse
    {
        if ($log->status !== 'Checked-In') {
            return redirect()->back()->with('error', 'This vehicle has already been checked out.');
        }

        DB::transaction(function () use ($log) {
            if ($log->parking_slot_id) {
                $slot = ParkingSlot::find($log->parking_slot_id);

                if ($slot) {
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
            }

            $log->update([
                'time_out' => now(),
                'status' => 'Checked-Out',
            ]);
        });

        return redirect()->back()->with('success', 'Vehicle checked out and parking slot released.');
    }

    public function confirmReservation(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'reserved' || $reservation->expires_at->lte(now())) {
            return redirect()->back()->with('error', 'This reservation is no longer active.');
        }

        $slot = ParkingSlot::findOrFail($reservation->parking_slot_id);

        if ($slot->status !== 'reserved') {
            return redirect()->back()->with('error', 'This slot is no longer reserved.');
        }

        DB::transaction(function () use ($reservation, $slot) {
            VehicleLog::create([
                'plate_number' => strtoupper($reservation->plate_number),
                'driver_name' => $reservation->user?->name ?? 'Reserved Driver',
                'vehicle_type' => $reservation->vehicle_type,
                'parking_zone_id' => $slot->parking_zone_id,
                'parking_slot_id' => $slot->id,
                'guard_id' => Auth::id(),
                'time_in' => now(),
                'status' => 'Checked-In',
            ]);

            $reservation->update(['status' => 'occupied']);
            $slot->update(['status' => 'occupied', 'is_occupied' => true]);

            $zone = $slot->zone;
            $zone->update(['available_slots' => $zone->slots()->where('status', 'available')->count()]);
        });

        return redirect()->back()->with('success', 'Reservation confirmed and vehicle checked in.');
    }
}