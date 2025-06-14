<?php

use App\Http\Controllers\Api\Admin\Categories\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\Permissions\PermissionController;
use App\Http\Controllers\Api\Admin\Posts\PostController;
use App\Http\Controllers\Api\Admin\Roles\RoleController;
use App\Http\Controllers\Api\Admin\Series\SeriesController;
use App\Http\Controllers\Api\Admin\Users\ProfileController;
use App\Http\Controllers\Api\Admin\Users\UserController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for admin panel.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group and requires authentication.
|
*/



// Public API routes for Vue.js (temporary - will be moved to authenticated routes later)
Route::prefix('v1/admin')->name('api.admin.public.')->group(function () {



    // Users API
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/list', [UserController::class, 'index'])->name('list');
        Route::get('/find/{id}', [UserController::class, 'show'])->name('find');
        Route::post('/create', [UserController::class, 'store'])->name('create');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::patch('/status/{id}', [UserController::class, 'toggleStatus'])->name('status');
        Route::post('/assign-roles/{id}', [UserController::class, 'assignRoles'])->name('assign-roles');
        Route::get('/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete');
    });

    // Profiles API
    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::get('/find/{user_id}', [ProfileController::class, 'show'])->name('find');
        Route::put('/update/{user_id}', [ProfileController::class, 'update'])->name('update');
    });

    // Status API
    Route::get('/status/options', function () {
        return response()->json([
            'success' => true,
            'data' => [
                ['value' => 1, 'label' => 'Hoạt động'],
                ['value' => 0, 'label' => 'Không hoạt động']
            ]
        ]);
    })->name('status.options');

    // Roles API
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/list', [RoleController::class, 'index'])->name('list');
        Route::get('/find/{id}', [RoleController::class, 'show'])->name('find');
        Route::post('/create', [RoleController::class, 'store'])->name('create');
        Route::put('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
        Route::get('/autocomplete', [RoleController::class, 'autocomplete'])->name('autocomplete');
    });

    // Permissions API
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/list', [PermissionController::class, 'index'])->name('list');
        Route::get('/find/{id}', [PermissionController::class, 'show'])->name('find');
        Route::post('/create', [PermissionController::class, 'store'])->name('create');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('delete');
        Route::get('/autocomplete', [PermissionController::class, 'autocomplete'])->name('autocomplete');
    });

    // Categories API
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/list', [CategoryController::class, 'index'])->name('list');
        Route::get('/find/{id}', [CategoryController::class, 'show'])->name('find');
        Route::post('/create', [CategoryController::class, 'store'])->name('create');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
        Route::patch('/status/{id}', [CategoryController::class, 'toggleStatus'])->name('status');
    });

    // Series API
    Route::prefix('series')->name('series.')->group(function () {
        Route::get('/list', [SeriesController::class, 'index'])->name('list');
        Route::get('/find/{id}', [SeriesController::class, 'show'])->name('find');
        Route::post('/create', [SeriesController::class, 'store'])->name('create');
        Route::put('/update/{id}', [SeriesController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SeriesController::class, 'destroy'])->name('delete');
        Route::patch('/status/{id}', [SeriesController::class, 'toggleStatus'])->name('status');
        Route::get('/autocomplete', [SeriesController::class, 'autocomplete'])->name('autocomplete');
    });

    // Posts API
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/list', [PostController::class, 'index'])->name('list');
        Route::get('/find/{id}', [PostController::class, 'show'])->name('find');
        Route::post('/create', [PostController::class, 'store'])->name('create');
        Route::put('/update/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PostController::class, 'destroy'])->name('delete');
        Route::patch('/status/{id}', [PostController::class, 'toggleStatus'])->name('status');
    });

    // Profile API
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/info', [ProfileController::class, 'show'])->name('info');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::get('/stats', [ProfileController::class, 'stats'])->name('stats');
        Route::get('/activities', [ProfileController::class, 'activities'])->name('activities');
    });

    // Dashboard API
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('/recent-users', [DashboardController::class, 'recentUsers'])->name('recent-users');
        Route::get('/recent-posts', [DashboardController::class, 'recentPosts'])->name('recent-posts');
    });
});

// Authenticated API routes (for future use)
Route::prefix('v1/admin')->name('api.admin.')->middleware(['auth:sanctum'])->group(function () {
    // Will be implemented later with proper authentication
});
