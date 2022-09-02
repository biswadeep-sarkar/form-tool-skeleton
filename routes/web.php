<?php

use App\Http\Controllers\Admin\DashboardController;
// Middlewares
use App\Http\Controllers\Admin\DemoController;
// Controllers
use Biswadeep\FormTool\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* FormTool Admin Routes */
Route::group(['prefix' => config('form-tool.adminURL'), 'middleware' => [AdminAuth::class]], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::resource('demo-pages', DemoController::class);
});
