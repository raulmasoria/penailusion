<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RoundDayController;
use App\Http\Controllers\Api\DeviceUserTokenController;
use App\Http\Controllers\IntolerancesController;

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
Route::post('/auth/updateNotifications', [AuthController::class, 'updateNotifications']);
Route::post('/auth/updatePostalNotification', [AuthController::class, 'updatePostalNotification']);

/**
 *  Rondas
 */
Route::get('/auth/rounds', [RoundDayController::class, 'index']);
Route::get('/auth/rounds/day/{day}', [RoundDayController::class, 'roundsByDay']);
Route::get('/auth/rounds/companie/{id}', [RoundDayController::class, 'roundsByCompanie']);
Route::get('/auth/listbar/rounds', [RoundDayController::class, 'showBarsWithRound']);
Route::get('/auth/day/companies/{day}', [RoundDayController::class, 'showBarsByDay']);
Route::post('/auth/updateRound/{roundDay}', [RoundDayController::class, 'update']);

/**
 *  Notifications
 */
Route::post('/auth/device/token', [DeviceUserTokenController::class, 'store']);
Route::get('/auth/notification/user', [NotificationController::class, 'listByUser']);
Route::post('/auth/notification/send', [NotificationController::class, 'create']);
// Route::patch('/auth/notification/read', [NotificationController::class, 'updateReadNotification']);
// Route::patch('/auth/notification/read/all', [NotificationController::class, 'updateReadAllNotification']);
// Route::post('/auth/notification/count', [NotificationController::class, 'countUnreadNotification']);

/**
 *  Intolerances
 */
Route::get('/intolerances', [IntolerancesController::class, 'index']);
Route::get('/auth/intolerances/user', [IntolerancesController::class, 'showByUser']);
Route::post('/auth/intolerances', [IntolerancesController::class, 'update']);
