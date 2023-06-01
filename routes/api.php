<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('/auth/getUser', [AuthController::class, 'getUser']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/updateUser', [AuthController::class, 'updateUser']);
Route::post('/auth/updatePasswd', [AuthController::class, 'updatePasswd']);
Route::post('/auth/resetPasswd', [AuthController::class, 'resetPasswd']);
Route::post('/auth/resetPasswd', [AuthController::class, 'resetPasswd']);

Route::post('/auth/updateNotifications', [AuthController::class, 'updateNotifications']);