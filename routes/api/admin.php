<?php

use App\Http\Controllers\Api\Admin\Categories\CategoryController;
use App\Http\Controllers\Api\Admin\Permissions\PermissionController;
use App\Http\Controllers\Api\Admin\Posts\PostController;
use App\Http\Controllers\Api\Admin\Roles\RoleController;
use App\Http\Controllers\Api\Admin\Series\SeriesController;
use App\Http\Controllers\Api\Admin\Users\ProfileController;
use App\Http\Controllers\Api\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;

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

// Test route without auth
Route::prefix('v1/admin')->name('api.admin.')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API is working!', 'timestamp' => now()]);
    })->name('test');

    // Temporary users route without auth for testing
    Route::get('/users', [UserController::class, 'index'])->name('users.index.temp');
});

Route::middleware(['auth:sanctum'])->prefix('v1/admin')->name('api.admin.')->group(function () {

    // Users API
    Route::prefix('users')->name('users.')->group(function () {
        Route::middleware(['canAny:view_users'])->get('/', [UserController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_users'])->get('/{id}', [UserController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_users'])->post('/', [UserController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_users'])->put('/{id}', [UserController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_users'])->delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::middleware(['canAny:edit_users'])->patch('/{id}/status', [UserController::class, 'changeStatus'])->name('change.status');
        Route::middleware(['canAny:assign_users'])->post('/{id}/roles', [UserController::class, 'assignRoles'])->name('assign.roles');
        Route::middleware(['canAny:view_users'])->get('/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete');
    });

    // Profiles API
    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::middleware(['canAny:edit_users'])->get('/{user_id}', [ProfileController::class, 'show'])->name('show');
        Route::middleware(['canAny:edit_users'])->put('/{user_id}', [ProfileController::class, 'update'])->name('update');
    });

    // Roles API
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::middleware(['canAny:view_roles'])->get('/', [RoleController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_roles'])->get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_roles'])->post('/', [RoleController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_roles'])->put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_roles'])->delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::middleware(['canAny:view_roles'])->get('/autocomplete', [RoleController::class, 'autocomplete'])->name('autocomplete');
    });

    // Permissions API
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::middleware(['canAny:view_permissions'])->get('/', [PermissionController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_permissions'])->get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_permissions'])->post('/', [PermissionController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_permissions'])->put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_permissions'])->delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::middleware(['canAny:view_permissions'])->get('/autocomplete', [PermissionController::class, 'autocomplete'])->name('autocomplete');
    });

    // Categories API
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/', [CategoryController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_declarations'])->get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_declarations'])->post('/', [CategoryController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_declarations'])->put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Series API
    Route::prefix('series')->name('series.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/', [SeriesController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_declarations'])->get('/{id}', [SeriesController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_declarations'])->post('/', [SeriesController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_declarations'])->put('/{id}', [SeriesController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/{id}', [SeriesController::class, 'destroy'])->name('destroy');
        Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [SeriesController::class, 'autocomplete'])->name('autocomplete');
    });

    // Posts API
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/', [PostController::class, 'index'])->name('index');
        Route::middleware(['canAny:view_declarations'])->get('/{id}', [PostController::class, 'show'])->name('show');
        Route::middleware(['canAny:create_declarations'])->post('/', [PostController::class, 'store'])->name('store');
        Route::middleware(['canAny:edit_declarations'])->put('/{id}', [PostController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });
});
