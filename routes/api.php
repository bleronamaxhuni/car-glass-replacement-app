<?php

use App\Http\Controllers\Api\CarApiController;
use Illuminate\Support\Facades\Route;

Route::get('/car/makes', [CarApiController::class, 'makes']);
Route::get('/car/models', [CarApiController::class, 'models']);
Route::get('/car/years', [CarApiController::class, 'years']);
Route::get('/car/body-types', [CarApiController::class, 'bodyTypes']);

