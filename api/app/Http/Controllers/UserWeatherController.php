<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherApiService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserWeatherController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            $tempUnit = $request->get('isCelsius', 1) == 1 ? 'metric' : 'imperial';

            $users = User::query()->limit(5)->get();


            return response()->json([
                'data' => ['users' => $users, 'weathers' => $weathers],
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
