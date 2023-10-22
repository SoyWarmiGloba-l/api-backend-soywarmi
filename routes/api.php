<?php

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route group with middleware and prefinx
Route::middleware('api')->prefix('v1')->group(function () {
    Route::resource('teams', TeamController::class);
    Route::resource('people', PersonController::class);
    Route::resource('doctors', DoctorController::class);
});
