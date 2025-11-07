<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserProfileController;
use App\HTTP\Controller\WorkoutController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//forgot pass
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

//profile
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    Route::put('/user/profile', [UserProfileController::class, 'update']);
});

//workout schedule
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/workouts', [WorkoutController::class, 'index']);
    Route::get('/workouts/{id}', [WorkoutController::class, 'show']);
    Route::post('/workouts/checkin', [WorkoutController::class, 'checkIn']);
    Route::post('/workouts', [WorkoutController::class, 'store']);
});
