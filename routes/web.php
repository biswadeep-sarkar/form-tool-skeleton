<?php

use Illuminate\Support\Facades\Route;
use Biswadeep\FormTool\Support\CrudRoute;

// Middlewares
use Biswadeep\FormTool\Http\Middleware\AdminAuth;
use Biswadeep\FormTool\Http\Middleware\GuardRequest;

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
Route::middleware([AdminAuth::class, GuardRequest::class])->prefix(config('form-tool.adminURL'))->name('')->group(function () {
    
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    CrudRoute::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    CrudRoute::resource('user-groups', \App\Http\Controllers\Admin\UserGroupsController::class);

    CrudRoute::indexAndUpdate('settings', \App\Http\Controllers\Admin\SettingsController::class);
    CrudRoute::indexAndUpdate('change-password', \App\Http\Controllers\Admin\ChangePasswordController::class, '/{id}');
});