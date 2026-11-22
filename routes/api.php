<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleLogController;

// Public Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Requires a valid Sanctum Token)
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated user endpoint
    Route::get('/user', [AuthController::class, 'user']);
    
    // Logout endpoint
    Route::post('/logout', [AuthController::class, 'logout']);

    // Vehicle Log API Routes (Protected)[cite: 1, 2]
    Route::apiResource('vehicle-logs', VehicleLogController::class);
});