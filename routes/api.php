<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/send-notification',[NotificationController::class,'sendPushNotification']);
// Route::get('/generate-token',[NotificationController::class,'generateFirebaseAccessToken']);
// Route::post('/send', [NotificationController::class, "sendNotification"]);
Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
Route::post('/send-multicast-notification', [NotificationController::class, 'sendMulticastNotification']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
