<?php

use Illuminate\Support\Facades\Route;
use Biswadeep\FormTool\Support\CrudRoute;

// Middlewares
use Biswadeep\FormTool\Http\Middleware\AdminAuth;
use Biswadeep\FormTool\Http\Middleware\GuardRequest;

// Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemoController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\UserGroupsController;

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

// Admin Routes
// The name of the route works with Guard class for user permissions
Route::group(['prefix' => config('form-tool.adminURL'), 'middleware' => [AdminAuth::class, GuardRequest::class]], function ()
{
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    CrudRoute::resource('demo-pages', UsersController::class);

    CrudRoute::resource('users', UsersController::class);

    CrudRoute::resource('user-groups', UserGroupsController::class);

    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    Route::put('change-password/{id}', [ChangePasswordController::class, 'update'])->name('change-password');
});