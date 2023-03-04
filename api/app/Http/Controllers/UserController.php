<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        try {
            $users = Cache::remember('users', 3600, function () {
                return User::all();
            });
            return response()->json([
                'data' => ['users' => $users],
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
