<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * @return Response|null
     */
    public function index(): ?Response
    {
        try {
            $users = Cache::remember('users', 3600, static function () {
                return User::all();
            });

            return $this->successResponse(['users' => $users], 'Fetched successfully');
        } catch (Exception $exception) {
            return $this->errorResponse('Something went wrong!');
        }
    }
}
