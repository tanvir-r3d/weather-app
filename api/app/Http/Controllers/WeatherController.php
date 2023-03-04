<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherApiService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WeatherController
{
    public function index(Request $request)
    {
        try {
            $tempUnit = $request->get('isCelsius', 1) == 1 ? 'metric' : 'imperial';
            $limit = $request->get('limit');
            $offset = $request->get('offset');
            $users = User::query()->limit($limit)->offset($offset)->get();
            $responses = WeatherApiService::init();
            $responses->units = $tempUnit;
            $responses = $responses->fetchBulk($users);
            $weathers = collect($responses)->map(function ($item) {
                return [
                    'temp' => $item['main']['temp'],
                    'temp_max' => $item['main']['temp_max'],
                    'temp_min' => $item['main']['temp_min'],
                    'weather' => ucfirst($item['weather'][0]['description']),
                    'icon' => $item['weather'][0]['icon'],
                    'feels_like' => $item['main']['feels_like'],
                    'humidity' => $item['main']['humidity'],
                ];
            })->values()->toArray();

            return response()->json([
                'data' => ['weathers' => $weathers],
                'message' => 'Fetched successfully',
                'code' => Response::HTTP_OK,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }
}
