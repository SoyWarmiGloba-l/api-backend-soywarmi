<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ResourceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => 'web'], function () {

    Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
        Route::post('/resource/save', [ResourceController::class, 'saveResource'])->name('resource.save');
        Route::get('/resource', [ResourceController::class, 'index'])->name('resource');
        Route::get('/resource/{resource}', [ResourceController::class, 'deleteResource'])->name('resource.delete');
    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
