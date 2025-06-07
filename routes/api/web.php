<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Home\Posts\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for web application.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Public API routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');
        
        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/user', [AuthController::class, 'user'])->name('user');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        });
    });

    // Public posts API
    Route::prefix('posts')->name('api.posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
    });
});
