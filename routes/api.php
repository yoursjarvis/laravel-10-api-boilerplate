<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Users\AdminController;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [LoginController::class, '__invoke'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::group([
        'as'         => 'auth.',
        'prefix'     => '/auth',
    ], function () {
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    Route::group([
        'as'         => 'admins.',
        'controller' => AdminController::class,
        'prefix'     => '/admins',
    ], function () {
        Route::get('/', 'index')->name('index');
        Route::put('/{id}/restore', 'restore')->name('restore');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/store', 'store')->name('store');
        Route::delete('/{id}', 'trashed')->name('trashed');
        Route::put('/{id}', 'update')->name('update');
    });
});
