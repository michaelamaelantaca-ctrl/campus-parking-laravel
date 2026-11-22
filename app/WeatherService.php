<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Return the current weather for the configured city.
     *
     * @return array<string, mixed>|null
     */
    public function getCurrentWeather(): ?array
    {
        $city = config('services.weather.city', 'Arakan, North Cotabato');

        try {
            $query = $city;
            if (str_contains(strtolower($city), 'arakan')) {
                $query = 'Cotabato City, Philippines';
            }

            $geocodeResponse = Http::acceptJson()
                ->timeout(10)
                ->connectTimeout(5)
                ->get(config('services.weather.url', 'https://geocoding-api.open-meteo.com/v1/search'), [
                    'name' => $query,
                    'count' => 1,
                    'language' => 'en',
                    'format' => 'json',
                ]);

            if (! $geocodeResponse->ok()) {
                return null;
            }

            $locationData = $geocodeResponse->json('results.0');

            if (! is_array($locationData) || empty($locationData['latitude']) || empty($locationData['longitude'])) {
                return null;
            }

            $currentResponse = Http::acceptJson()
                ->timeout(10)
                ->connectTimeout(5)
                ->get(config('services.weather.current_url', 'https://api.open-meteo.com/v1/forecast'), [
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                    'timezone' => 'auto',
                    'forecast_days' => 1,
                ]);

            if (! $currentResponse->ok()) {
                return null;
            }

            $currentData = $currentResponse->json('current');

            if (! is_array($currentData) || ! array_key_exists('temperature_2m', $currentData)) {
                return null;
            }

            return [
                'location' => 'Arakan, North Cotabato',
                'temperature' => round((float) ($currentData['temperature_2m'] ?? 0), 1),
                'description' => $this->mapWeatherCode((int) ($currentData['weather_code'] ?? 0)),
                'humidity' => (int) ($currentData['relative_humidity_2m'] ?? 0),
                'wind_speed' => round((float) ($currentData['wind_speed_10m'] ?? 0), 1),
            ];
        } catch (\Throwable $exception) {
            Log::error('Weather API request failed.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    protected function mapWeatherCode(int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1, 2 => 'Partly cloudy',
            3 => 'Overcast',
            45, 48 => 'Foggy',
            51, 53, 55, 56, 57 => 'Drizzle',
            61, 63, 65, 66, 67 => 'Rain',
            71, 73, 75, 77, 79 => 'Snow',
            80, 81, 82 => 'Rain showers',
            85, 86 => 'Snow showers',
            95, 96, 99 => 'Thunderstorm',
            default => 'Weather unavailable',
        };
    }
}
