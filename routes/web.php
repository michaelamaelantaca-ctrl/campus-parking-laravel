<?php

use App\Http\Controllers\Admin\ParkingSlotController;
use App\Http\Controllers\Admin\ParkingZoneController;
use App\Http\Controllers\Admin\VehicleLogController;
use App\Http\Controllers\Driver\ParkingController;
use App\Http\Controllers\Guard\GateLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $zones = ParkingZone::where('is_active', true)
        ->with('slots')
        ->orderBy('name')
        ->take(6)
        ->get();

    $zoneStats = [
        'active_zones' => $zones->count(),
        'available_slots' => $zones->sum(fn (ParkingZone $zone) => $zone->slots->where('status', 'available')->count()),
        'total_slots' => $zones->sum(fn (ParkingZone $zone) => $zone->slots->count()),
    ];

    return view('welcome', compact('zones', 'zoneStats'));
});

// Central Redirection Route
Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'guard') {
        return redirect()->route('guard.dashboard');
    }

    return redirect()->route('driver.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Management Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Admin Routes (Includes Dashboard, Zones, Slots, and Vehicle Logs CRUD)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $weather = [
            'location' => 'Arakan, North Cotabato',
            'temperature' => '26.6°C',
            'condition' => 'Overcast',
            'humidity' => '83%',
            'wind_speed' => '12.1 km/h',
        ];

        $usage = [
            'zones' => ParkingZone::count(),
            'active_zones' => ParkingZone::where('is_active', true)->count(),
            'available_slots' => ParkingSlot::where('status', 'available')->count(),
            'occupied_slots' => ParkingSlot::where('status', 'occupied')->count(),
            'active_reservations' => Reservation::where('status', 'reserved')->where('expires_at', '>', now())->count(),
            'today_entries' => VehicleLog::whereDate('time_in', today())->count(),
            'today_checked_out' => VehicleLog::whereDate('time_in', today())->where('status', 'Checked-Out')->count(),
        ];

        $recentLogs = VehicleLog::with(['zone', 'slot', 'securityGuard'])->latest()->take(8)->get();

        return view('admin.dashboard', compact('weather', 'usage', 'recentLogs'));
    })->name('dashboard');

    Route::resource('zones', ParkingZoneController::class);
    Route::patch('/zones/{zone}/toggle', [ParkingZoneController::class, 'toggle'])->name('zones.toggle');

    Route::get('/reports/daily', function (Request $request) {
        $date = $request->input('date', now()->toDateString());
        $logs = VehicleLog::with(['zone', 'slot', 'securityGuard'])
            ->whereDate('time_in', $date)
            ->latest('time_in')
            ->get();

        $summary = [
            'entries' => $logs->count(),
            'checked_out' => $logs->where('status', 'Checked-Out')->count(),
            'currently_parked' => $logs->where('status', 'Checked-In')->count(),
            'zones_used' => $logs->pluck('parking_zone_id')->filter()->unique()->count(),
        ];

        return view('admin.reports.daily', compact('logs', 'summary', 'date'));
    })->name('reports.daily');

    // Slot Management Routes
    Route::get('/zones/{zone}/slots', [ParkingSlotController::class, 'index'])->name('zones.slots.index');
    Route::post('/zones/{zone}/slots/generate', [ParkingSlotController::class, 'generate'])->name('zones.slots.generate');

    // Vehicle Logs CRUD Routes
    Route::resource('vehicle-logs', VehicleLogController::class);
});

// Security Guard Routes
Route::middleware(['auth', 'role:guard'])->prefix('guard')->name('guard.')->group(function () {
    Route::get('/dashboard', [GateLogController::class, 'index'])->name('dashboard');
    Route::get('/logs', [GateLogController::class, 'logs'])->name('logs');

    // API Route para sa dynamic dropdown ng zones at slots
    Route::get('/api/zones-slots', [GateLogController::class, 'getZonesAndSlots'])->name('api.zones-slots');

    Route::post('/entry', [GateLogController::class, 'store'])->name('check.in');
    Route::patch('/exit/{log}', [GateLogController::class, 'update'])->name('check.out');
    Route::patch('/reservations/{reservation}/confirm', [GateLogController::class, 'confirmReservation'])->name('reservations.confirm');
});

// Driver Routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {

    Route::get('/dashboard', [ParkingController::class, 'index'])->name('dashboard');
    Route::get('/zones', [ParkingController::class, 'index'])->name('zones');
    Route::get('/zones/{zone}/slots', [ParkingController::class, 'showSlots'])->name('slots');
    Route::post('/slots/{slot}/reserve', [ParkingController::class, 'reserveSlot'])->name('slots.reserve');
    Route::delete('/reservations/{reservation}', [ParkingController::class, 'cancelReservation'])->name('reservations.cancel');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Terms and Conditions Route
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

require __DIR__.'/auth.php';