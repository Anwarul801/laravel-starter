<?php

/**
 * @Author: Anwarul
 * @Date: 2025-11-17 14:53:56
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-04-15 17:52:38
 * @Description: Innova IT
 */

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/page_view/{slug}', [HomeController::class, 'page_view'])->name('page_view');
Route::get('users-search', [HomeController::class, 'users_search'])->name('users_search')->withoutMiddleware('auth');

Route::get('/admin/users/{user}/login-as', [UsersController::class, 'loginAs'])
    ->name('users.login-as');


Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group([
        'middleware' => ['auth:admin', 'permission']
    ], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::resource('roles', RolesController::class);
        Route::resource('users', UsersController::class);
        Route::resource('admin', AdminController::class);
        Route::resource('permissions', PermissionsController::class);
        Route::resource('division', DivisionController::class);
        Route::resource('district', DistrictController::class);
        Route::resource('thana', ThanaController::class);
        Route::resource('setting', SettingController::class);
        Route::resource('page', PageController::class);
    });
});
