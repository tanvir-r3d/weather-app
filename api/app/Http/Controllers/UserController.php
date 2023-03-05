<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = Cache::remember('users', 3600, function () {
                return User::all();
            });
            return $this->successResponse(['users' => $users], 'Fetched successfully');
        } catch (Exception $exception) {
            return $this->errorResponse('Something went wrong!');
        }
    }
}
