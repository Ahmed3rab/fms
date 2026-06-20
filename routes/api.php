<?php

use App\Http\Controllers\API\V1\DeviceController;
use App\Http\Controllers\API\V1\DeviceStateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::get('/devices', [DeviceController::class, 'index']);

        Route::get('/devices/{device:uuid}', [DeviceController::class, 'show']);

        Route::get('/devices/{device:uuid}/state', DeviceStateController::class);
    });
