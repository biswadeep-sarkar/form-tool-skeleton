<?php

use Illuminate\Support\Facades\Route;

// Middlewares
use Biswadeep\FormTool\Http\Middleware\AdminAuth;

// Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemoController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\UsersController;

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

    Route::get('/change-password', [ChangePasswordController::class, 'index']);
    Route::put('change-password/{id}', [ChangePasswordController::class, 'update']);

    Route::get('users/search', [UsersController::class, 'search']);
    Route::resource('users', UsersController::class);
});