<?php

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\MedicalCenterController;
use App\Http\Controllers\Api\MedicalServiceController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChatController;

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
Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::resource('teams', TeamController::class);
    Route::resource('people', PersonController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('medical_centers', MedicalCenterController::class);
    Route::resource('medical_services', MedicalServiceController::class);
});
Route::post('auth/login', [AuthController::class, 'login']);
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
