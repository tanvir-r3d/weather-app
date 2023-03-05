<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherApiService;
use App\Services\WeatherFormatter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Exception;

class WeatherController extends Controller
{
    /**
     * @param string $email
     * @return Response
     */
    public function index(string $email): Response
    {
        try {
            $weather = Cache::remember($email, 3600, static function () use ($email) {
                $user = User::query()->where('email', $email)->first();
                $weather = null;
                if ($user) {
                    $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
                    $weather->units = 'metric';
                    $weather = $weather->fetchCurrent();
                }
                if ($weather) {
                    return $weather;
                }
            });

            $response = WeatherFormatter::format($weather);

            return $this->successResponse(['weather' => $response], 'Fetched successfully');
        } catch (Exception $exception) {
            return $this->errorResponse('Something went wrong!');
        }
    }

    /**
     * @param $email
     * @return Response
     */
    public function getDetails($email): Response
    {
        try {
            $weather = WeatherFormatter::format(Cache::get($email));
            $details = Cache::remember($email . '_forecast', 3600, static function () use ($email) {
                $user = User::query()->where('email', $email)->first();
                $weather = null;
                if ($user) {
                    $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
                    $weather->units = 'metric';
                    $weather->cnt = 4;
                    $weather = $weather->fetchForecast();
                }
                if ($weather) {
                    return $weather;
                }
            });
            $details = WeatherFormatter::formatDetails($details['list']);
            return $this->successResponse(['weather' => $weather, 'details' => $details], 'Fetched successfully.');
        } catch (Exception $exception) {
            return $this->errorResponse('Something went wrong!');
        }
    }
}
