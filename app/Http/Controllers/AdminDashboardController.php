<?php

namespace App\Http\Controllers;

use App\WeatherService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(protected WeatherService $weatherService)
    {
        //
    }

    public function index(Request $request)
    {
        $weather = $this->weatherService->getCurrentWeather();

        return view('admin.dashboard', [
            'weather' => $weather,
            'weatherError' => $weather === null ? 'Weather data is currently unavailable. Please try again later.' : null,
        ]);
    }
}
