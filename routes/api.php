<?php

use App\Http\Controllers\Api\EmailVerificationNotificationController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/registration', RegistrationController::class);
Route::post('/login', LoginController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('verified')->post('/subscribe', MainController::class);
    Route::middleware('throttle:6,1')->post('email/verification-notification', EmailVerificationNotificationController::class);
});
