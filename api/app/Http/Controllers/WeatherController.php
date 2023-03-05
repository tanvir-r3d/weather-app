<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherApiService;
use App\Services\WeatherFormatter;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    /**
     * @param string $email
     * @return Response
     */
    public function index(string $email): Response
    {
        try {
            $weather = Cache::remember($email, 3600, function () use ($email) {
                $user = User::query()->where('email', $email)->first();
                $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
                $weather->units = 'metric';
                return $weather->fetchCurrent();
            });

            $response = WeatherFormatter::format($weather);

            return $this->successResponse(['weather' => $response], 'Fetched successfully');
        } catch (Exception $exception) {
            return $this->errorResponse('Something went wrong!');
        }
    }

    public function getDetails($email)
    {
        $weather = WeatherFormatter::format(Cache::get($email));
        $details = Cache::remember($email . '_forecast', 3600, function () use ($email) {
            $user = User::query()->where('email', $email)->first();
            $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
            $weather->units = 'metric';
            $weather->cnt = 5;
            return $weather->fetchForecast();
        });
        $details = WeatherFormatter::formatDetails($details['list']);
        return $weather;
    }
}
