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
        // try {
            // $tempUnit = $request->get('isCelsius', 1) == 1 ? 'metric' : 'imperial';

            $users = User::all()->map(function ($user) {
                $weather = WeatherApiService::init()->setLocation($user->latitude, $user->longitude);
                $weather->start = date('Y-m-d');
                $weather->end = date('Y-m-d');
                $response = $weather->fetchCurrent();
                dump($response);
                $response = $response->getBody()->getContents();
                $response = json_decode($response, true)['data'][0];
                $user['temperature'] = $response['temp'];
                $user['feels_like'] = $response['app_temp'];
                $user['city_name'] = $response['city_name'];
                $user['localtime'] = $response['ob_time'];
                $user['weather'] = $response['weather']['description'];
                $user['weather_icon'] = "https://www.weatherbit.io/static/img/icons/".$response['weather']['icon'].".png";
                return $user;
            });

            return response()->json([
                'data' => $users,
                'message' => 'Fetched successfully',
                'code' => Response::HTTP_OK,
            ]);
        // } catch (Exception $exception) {
        //     return response()->json([
        //         'message' => 'Something went wrong!',
        //         'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        //     ]);
        // }
    }
}
