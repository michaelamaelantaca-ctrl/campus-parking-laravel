<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleLogController extends Controller
{
    // READ ALL: Kuhaa ang tanang vehicle logs
    public function index()
    {
        $logs = VehicleLog::all();
        return response()->json([
            'success' => true,
            'message' => 'Nakuha ang tanang vehicle logs.',
            'data' => $logs
        ], 200);
    }

    // CREATE: Pagdugang og bag-ong vehicle log (POST)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plate_number' => 'required|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'time_in' => 'required|date',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Sayop sa validation ang gihimo.',
                'errors' => $validator->errors()
            ], 422);
        }

        $log = VehicleLog::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Nailista na ang bag-ong sakyanan.',
            'data' => $log
        ], 201);
    }

    // READ ONE: Kuhaa ang usa ka piho nga log base sa ID (GET)
    public function show($id)
    {
        $log = VehicleLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Wala makit-an ang maong vehicle log.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nakuha ang detalye sa vehicle log.',
            'data' => $log
        ], 200);
    }

    // UPDATE: I-update ang kasamtangan nga record (PUT/PATCH)
    public function update(Request $request, $id)
    {
        $log = VehicleLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Wala makit-an ang vehicle log para i-update.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'plate_number' => 'sometimes|required|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'time_in' => 'sometimes|required|date',
            'time_out' => 'nullable|date',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Sayop sa validation sa pag-update.',
                'errors' => $validator->errors()
            ], 422);
        }

        $log->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Na-update na ang vehicle log.',
            'data' => $log
        ], 200);
    }

    // DELETE: Pagtangtang sa record (DELETE)
    public function destroy($id)
    {
        $log = VehicleLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Wala makit-an ang vehicle log para tangtangon.'
            ], 404);
        }

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nangtanggal na ang vehicle log.'
        ], 200);
    }
}