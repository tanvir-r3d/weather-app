<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/weathers/{email}', [WeatherController::class, 'index']);
    Route::get('/weathers/{email}/details', [WeatherController::class, 'getDetails']);
});
