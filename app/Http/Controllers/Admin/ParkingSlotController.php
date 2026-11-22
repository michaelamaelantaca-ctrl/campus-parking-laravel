<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;

class ParkingSlotController extends Controller
{
    /**
     * Display a listing of the parking slots for a specific zone.
     */
    public function index(ParkingZone $zone)
    {
        $slots = $zone->slots()
            ->get()
            ->sortBy(fn (ParkingSlot $slot) => (int) preg_replace('/\D+/', '', (string) $slot->slot_number))
            ->values();

        $zones = ParkingZone::with('slots')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function (ParkingZone $z) {
                $sorted = $z->slots
                    ->sortBy(fn (ParkingSlot $s) => (int) preg_replace('/\D+/', '', (string) $s->slot_number))
                    ->values();
                $z->setRelation('slots', $sorted);
            });

        return view('admin.slots.index', compact('zone', 'zones', 'slots'));
    }

    /**
     * Auto-generate parking slots based on total capacity.
     */
    public function generate(ParkingZone $zone)
    {
        $capacity = $zone->total_capacity ?? $zone->capacity ?? 0;

        for ($i = 1; $i <= $capacity; $i++) {
            $slotNumber = 'Z-'.str_pad($i, 2, '0', STR_PAD_LEFT);
            $rowIndex = intdiv($i - 1, 10);
            $rowLetter = chr(65 + $rowIndex);

            ParkingSlot::firstOrCreate(
                [
                    'parking_zone_id' => $zone->id,
                    'slot_number' => $slotNumber,
                ],
                [
                    'status' => 'available',
                    'row_label' => 'Row '.$rowLetter,
                    'position_order' => (($i - 1) % 10) + 1,
                ]
            );
        }

        return redirect()->route('admin.zones.slots.index', $zone->id)
            ->with('success', "Successfully generated slots for {$zone->name}!");
    }
}