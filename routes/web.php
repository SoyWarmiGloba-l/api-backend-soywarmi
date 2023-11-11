<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ResourceController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\ActivitiesController;
use App\Http\Controllers\Web\TestimonyController;
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
        Route::post('/activities/get', [ActivitiesController::class, 'getActivities'])->name('activities.get');
        Route::get('/activities/index', [ActivitiesController::class, 'index'])->name('activities.index');
        Route::post('/activities/save', [ActivitiesController::class, 'saveActivity'])->name('activities.save');
        Route::post('/activities/delete/{activity}', [ActivitiesController::class, 'deleteactivity'])->name('activity.delete');
        Route::get('/resource/{resource}', [ResourceController::class, 'deleteResource'])->name('resource.delete');
        Route::get('/testimony/index', [TestimonyController::class, 'index'])->name('testimony.index');
        Route::post('/testimony/save', [TestimonyController::class, 'save'])->name('testimony.save');
        Route::post('/testimony/get', [TestimonyController::class, 'getTestimony'])->name('testimony.get');
        Route::post('/testimony/delete/{testimony}', [TestimonyController::class, 'deleteTestimony'])->name('testimony.delete');
    });
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
