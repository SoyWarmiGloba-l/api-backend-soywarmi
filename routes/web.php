<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/resource', function () {
        return view('admin.resource_admin');
    })->name('resource');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
