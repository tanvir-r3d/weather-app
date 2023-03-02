<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherApiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserWeatherController extends Controller
{
    public function getUser(Request $request): JsonResponse
    {
        try {
            $tempUnit = $request->get('isCelsius', 1) == 1 ? 'metric' : 'imperial';

            $users = User::all()->map(function ($user) use ($tempUnit) {
                $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
                $weather->temp = $tempUnit;
//                $response = json_decode($weather->fetchCurrent()->getBody()->getContents(), true);
//                $currentWeather = $response['data'][0];
                dd($weather->fetchCurrent());
                $user['temperature'] = $currentWeather['temp'];
                $user['country'] = $currentWeather[''];
                $user['city_name'] = $currentWeather['city_name'];
                $user['weather'] = $currentWeather;
                $user['weather_icon'] = $currentWeather;
                return $user;
            });

            return response()->json([
                'data' => $users,
                'message' => 'Fetched successfully',
                'code' => Response::HTTP_OK,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong!',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }
}
