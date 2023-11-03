<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ResourceController;
use App\Http\Controllers\Web\NewsController;
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
        Route::post('/news/save', [NewsController::class, 'saveNews'])->name('news.save');
        Route::post('/news/delete/{news}', [NewsController::class, 'deleteNews'])->name('news.delete');
        Route::post('/news/get', [NewsController::class, 'getNews'])->name('news.get');
        Route::post('/resource/delete', [ResourceController::class, 'deleteResource'])->name('resource.delete');
        Route::post('/resource/save', [ResourceController::class, 'saveResource'])->name('resource.save');
        Route::get('/resource', [ResourceController::class, 'index'])->name('resource');
        Route::get('/news', [NewsController::class, 'index'])->name('news');
        Route::get('/resource/{resource}', [ResourceController::class, 'deleteResource'])->name('resource.delete');
    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
