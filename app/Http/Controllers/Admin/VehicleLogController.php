<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleLog;
use Illuminate\Http\Request;

class VehicleLogController extends Controller
{
    // READ: Display all logs
    public function index()
    {
        $logs = VehicleLog::with(['zone', 'slot', 'securityGuard'])->latest()->get();

        return view('admin.vehicle-logs.index', compact('logs'));
    }

    // CREATE: Show form to add new log
    public function create()
    {
        return view('admin.vehicle-logs.create');
    }

    // STORE: Save new log to database
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'time_in' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        VehicleLog::create($request->all());

        return redirect()->route('admin.vehicle-logs.index')->with('success', 'Vehicle log added successfully.');
    }

    // EDIT: Show form to update log
    public function edit(VehicleLog $vehicleLog)
    {
        return view('admin.vehicle-logs.edit', compact('vehicleLog'));
    }

    // UPDATE: Save changes to database
    public function update(Request $request, VehicleLog $vehicleLog)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'time_in' => 'required|date',
            'status' => 'required|string|max:50',
            'time_out' => 'nullable|date',
        ]);

        $vehicleLog->update($request->all());

        return redirect()->route('admin.vehicle-logs.index')->with('success', 'Vehicle log updated successfully.');
    }

    // DELETE: Remove log from database
    public function destroy(VehicleLog $vehicleLog)
    {
        $vehicleLog->delete();

        return redirect()->route('admin.vehicle-logs.index')->with('success', 'Vehicle log deleted successfully.');
    }
}
