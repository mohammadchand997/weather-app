<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        // Default location or get from GeoIP
        $city = $request->input('city') ?? $this->getUserCity();
        $weatherData = $this->fetchWeatherData($city);

        return view('weather.index', compact('city', 'weatherData'));
    }

    private function getUserCity()
    {
        $ip = request()->ip(); // Use '8.8.8.8' for testing
        $locationData = Http::get("http://ip-api.com/json/{$ip}")->json();

        return $locationData['city'] ?? 'Unknown';
    }

    private function fetchWeatherData($city)
    {
        $apiKey = env('WEATHER_API_KEY');
        $apiUrl = "http://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$apiKey}";

        try {
            $response = Http::get($apiUrl);
            if ($response->ok()) {
                // echo "<pre>";
                // print_r($response->json());
                // die;
                return $response->json();
            } else {
                return [
                    'error' => $response->json()['message'] ?? 'City not found!',
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'An error occurred while fetching weather data.',
            ];
        }
    }
}
