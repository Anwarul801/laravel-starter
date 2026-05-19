<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ThanaController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));

Auth::routes();

Route::middleware(['auth:admin', 'permission'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('users-search', [HomeController::class, 'users_search'])->name('users_search');
    Route::get('/page_view/{slug}', [HomeController::class, 'page_view'])->name('page_view');

    Route::resource('admin',       AdminController::class);
    Route::resource('users',       UsersController::class);
    Route::resource('roles',       RolesController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('division',    DivisionController::class);
    Route::resource('district',    DistrictController::class);
    Route::resource('thana',       ThanaController::class);
    Route::resource('setting',     SettingController::class);
    Route::resource('page',        PageController::class);
});
