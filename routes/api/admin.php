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



Route::prefix('v1/admin')->name('api.admin.')->group(function () {

    // Users API
    Route::prefix('users')->name('users.')->group(function () {
        Route::middleware(['canAny:view_users'])->get('/list', [UserController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_users'])->get('/find/{id}', [UserController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_users'])->post('/create', [UserController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_users'])->put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_users'])->delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::middleware(['canAny:edit_users'])->patch('/status/{id}', [UserController::class, 'changeStatus'])->name('status');
        Route::middleware(['canAny:assign_users'])->post('/roles/{id}', [UserController::class, 'assignRoles'])->name('roles');
        Route::middleware(['canAny:view_users'])->get('/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete');
    });

    // Profiles API
    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::middleware(['canAny:edit_users'])->get('/find/{user_id}', [ProfileController::class, 'show'])->name('find');
        Route::middleware(['canAny:edit_users'])->put('/update/{user_id}', [ProfileController::class, 'update'])->name('update');
    });

    // Roles API
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::middleware(['canAny:view_roles'])->get('/list', [RoleController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_roles'])->get('/find/{id}', [RoleController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_roles'])->post('/create', [RoleController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_roles'])->put('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_roles'])->delete('/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
        Route::middleware(['canAny:view_roles'])->get('/autocomplete', [RoleController::class, 'autocomplete'])->name('autocomplete');
    });

    // Permissions API
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::middleware(['canAny:view_permissions'])->get('/list', [PermissionController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_permissions'])->get('/find/{id}', [PermissionController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_permissions'])->post('/create', [PermissionController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_permissions'])->put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_permissions'])->delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('delete');
        Route::middleware(['canAny:view_permissions'])->get('/autocomplete', [PermissionController::class, 'autocomplete'])->name('autocomplete');
    });

    // Categories API
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/list', [CategoryController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_declarations'])->get('/find/{id}', [CategoryController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_declarations'])->post('/create', [CategoryController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_declarations'])->put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // Series API
    Route::prefix('series')->name('series.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/list', [SeriesController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_declarations'])->get('/find/{id}', [SeriesController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_declarations'])->post('/create', [SeriesController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_declarations'])->put('/update/{id}', [SeriesController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [SeriesController::class, 'destroy'])->name('delete');
        Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [SeriesController::class, 'autocomplete'])->name('autocomplete');
    });

    // Posts API
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::middleware(['canAny:view_declarations'])->get('/list', [PostController::class, 'index'])->name('list');
        Route::middleware(['canAny:view_declarations'])->get('/find/{id}', [PostController::class, 'show'])->name('find');
        Route::middleware(['canAny:create_declarations'])->post('/create', [PostController::class, 'store'])->name('create');
        Route::middleware(['canAny:edit_declarations'])->put('/update/{id}', [PostController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [PostController::class, 'destroy'])->name('delete');
    });
});
