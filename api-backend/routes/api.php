<?php

use App\Http\Controllers\Api\CustomerReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('reservations', CustomerReservationController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
