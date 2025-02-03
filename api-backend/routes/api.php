<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerReservationController;
use App\Http\Controllers\Api\EmailVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('reservations', CustomerReservationController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::controller(EmailVerificationController::class)->group(function () {
    Route::get('/email/verify', 'show')->middleware('auth:sanctum')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', 'resend')->middleware('throttle:6,1')->name('verification.resend');
});
