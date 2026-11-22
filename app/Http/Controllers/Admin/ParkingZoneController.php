<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingZone;
use Illuminate\Http\Request;

class ParkingZoneController extends Controller
{
    /**
     * Display a listing of the parking zones.
     */
    public function index()
    {
        $zones = ParkingZone::withCount('slots')->latest()->get();

        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new parking zone.
     */
    public function create()
    {
        return view('admin.zones.create');
    }

    /**
     * Store a newly created parking zone in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'map_x' => 'nullable|numeric|between:0,100',
            'map_y' => 'nullable|numeric|between:0,100',
            'total_capacity' => 'required|integer|min:1',
        ]);

        ParkingZone::create([
            'name' => $request->name,
            'location_description' => $request->location_description,
            'landmark' => $request->landmark,
            'map_x' => $request->map_x,
            'map_y' => $request->map_y,
            'total_capacity' => $request->total_capacity,
            'available_slots' => $request->total_capacity, // Idinagdag para masagot ang database constraint
        ]);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Parking zone created successfully.');
    }

    /**
     * Show the form for editing the specified parking zone.
     */
    public function edit(ParkingZone $zone)
    {
        return view('admin.zones.edit', compact('zone'));
    }

    /**
     * Update the specified parking zone in storage.
     */
    public function update(Request $request, ParkingZone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'map_x' => 'nullable|numeric|between:0,100',
            'map_y' => 'nullable|numeric|between:0,100',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $zone->update([
            'name' => $request->name,
            'location_description' => $request->location_description,
            'landmark' => $request->landmark,
            'map_x' => $request->map_x,
            'map_y' => $request->map_y,
            'total_capacity' => $request->total_capacity,
            // Opsyonal: Pwede mo rin i-adjust ang available_slots dito kung binago ang capacity
            'available_slots' => $request->total_capacity,
        ]);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Parking zone updated successfully.');
    }

    /**
     * Remove the specified parking zone from storage.
     */
    public function destroy(ParkingZone $zone)
    {
        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Parking zone deleted successfully.');
    }

    /**
     * Toggle the activation status of the specified parking zone.
     */
    public function toggle(ParkingZone $zone)
    {
        $zone->update([
            'is_active' => ! $zone->is_active,
        ]);

        $message = $zone->is_active
            ? "Parking zone {$zone->name} is now active."
            : "Parking zone {$zone->name} has been temporarily disabled.";

        return redirect()->route('admin.zones.index')->with('success', $message);
    }
}
