<?php

use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Permissions\PermissionController;
use App\Http\Controllers\Admin\Posts\PostController;
use App\Http\Controllers\Admin\Roles\RoleController;
use App\Http\Controllers\Admin\Series\SeriesController;
use App\Http\Controllers\Admin\Users\ProfileController;
use App\Http\Controllers\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard route
Route::get('/', function () {
    return view('admin.dashboard');
})->name('index');



    Route::prefix('users')->name('users.')->group(function () { // Chức năng quản lý tài khoản
        Route::middleware(['canAny:view_users'])->get('/index', [UserController::class, 'index'])->name('index'); // Hiển thị danh sách tài khoản
        Route::middleware(['canAny:create_users'])->get('/create', [UserController::class, 'create'])->name('create'); // Hiển thị form tạo tài khoản
        Route::middleware(['canAny:create_users'])->post('/store', [UserController::class, 'store'])->name('store'); // Xử lý tạo tài khoản
        Route::middleware(['canAny:edit_users'])->get('/edit/{id}', [UserController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa
        Route::middleware(['canAny:edit_users'])->post('/update/{id}', [UserController::class, 'update'])->name('update'); // Xử lý chỉnh sửa
        Route::middleware(['canAny:delete_users'])->post('/delete/{id}', [UserController::class, 'delete'])->name('delete'); // Xử lý xóa
        // 🚀 Hiển thị giao diện phân vai trò
        Route::middleware(['canAny:assign_users'])->get('/assign-roles/{id}', [UserController::class, 'showAssignRolesForm'])->name('showAssignRolesForm');
        // 🚀 Xử lý gán vai trò cho người dùng
        Route::middleware(['canAny:assign_users'])->post('/assign-roles/{id}', [UserController::class, 'assignRoles'])->name('assignRoles');
        Route::middleware(['canAny:edit_users'])->post('/toggle-block/{id}', [UserController::class, 'changeStatus'])->name('toggleBlock');
        Route::middleware(['canAny:view_users'])->get('/autocomplete', [UserController::class, 'autocomplete'])->name('autocomplete'); // Lấy vai trò theo từ
    });

    Route::prefix('profiles')->name('profiles.')->group(function () { // Chức năng quản lý hồ sơ
        Route::middleware(['canAny:edit_users'])->get('/edit/{user_id}', [ProfileController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa
        Route::middleware(['canAny:edit_users'])->post('/update/{user_id}', [ProfileController::class, 'update'])->name('update'); // Xử lý chỉnh sửa
    });

    Route::prefix('roles')->name('roles.')->group(function () { // Chức năng quản lý vai trò
        Route::middleware(['canAny:view_roles'])->get('/index', [RoleController::class, 'index'])->name('index'); // Hiển thị danh sách vai trò
        Route::middleware(['canAny:create_roles'])->get('/create', [RoleController::class, 'create'])->name('create'); // Hiển thị form tạo mới vai trò
        Route::middleware(['canAny:create_roles'])->post('/store', [RoleController::class, 'store'])->name('store'); // Xử lý thêm mới vai trò
        Route::middleware(['canAny:edit_roles'])->get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::middleware(['canAny:edit_roles'])->post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_roles'])->delete('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        Route::middleware(['canAny:view_roles'])->get('/autocomplete', [RoleController::class, 'autocomplete'])->name('autocomplete'); // Lấy vai trò theo từ
    });

    // Chức năng quản lý quyền
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::middleware(['canAny:view_permissions'])->get('/index', [PermissionController::class, 'index'])->name('index'); // Hiển thị danh sách quyền
        Route::middleware(['canAny:create_permissions'])->get('/create', [PermissionController::class, 'create'])->name('create'); // Hiển thị form tạo mới quyền
        Route::middleware(['canAny:create_permissions'])->post('/store', [PermissionController::class, 'store'])->name('store'); // Xử lý thêm mới quyền
        Route::middleware(['canAny:edit_permissions'])->get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit'); // Hiển thị form sửa quyền
        Route::middleware(['canAny:edit_permissions'])->post('/update/{id}', [PermissionController::class, 'update'])->name('update'); // Xử lý sửa quyền
        Route::middleware(['canAny:delete_permissions'])->delete('/delete/{id}', [PermissionController::class, 'delete'])->name('delete'); // Xử lý xóa quyền
        Route::middleware(['canAny:view_permissions'])->get('/autocomplete', [PermissionController::class, 'autocomplete'])->name('autocomplete'); // Lấy quyền theo từ
    });

    Route::prefix('categories')->name('categories.')->group(function () { // Chức năng quản lý danh mục
        Route::middleware(['canAny:view_declarations'])->get('/index', [CategoryController::class, 'index'])->name('index'); // Hiển thị danh sách danh mục
        Route::middleware(['canAny:create_declarations'])->get('/create', [CategoryController::class, 'create'])->name('create'); // Hiển thị form tạo mới danh mục
        Route::middleware(['canAny:create_declarations'])->post('/store', [CategoryController::class, 'store'])->name('store'); // Xử lý thêm mới danh mục
        Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit'); // Hiển thị form sửa danh mục
        Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [CategoryController::class, 'update'])->name('update'); // Xử lý sửa danh mục
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete'); // Xử lý xóa danh mục
    });

    Route::prefix('series')->name('series.')->group(function () { // Chức năng quản lý series
        Route::middleware(['canAny:view_declarations'])->get('/index', [SeriesController::class, 'index'])->name('index'); // Hiển thị danh sách series
        Route::middleware(['canAny:create_declarations'])->get('/create', [SeriesController::class, 'create'])->name('create'); // Hiển thị form tạo mới series
        Route::middleware(['canAny:create_declarations'])->post('/store', [SeriesController::class, 'store'])->name('store'); // Xử lý thêm mới series
        Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [SeriesController::class, 'edit'])->name('edit'); // Hiển thị form sửa series
        Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [SeriesController::class, 'update'])->name('update'); // Xử lý sửa series
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [SeriesController::class, 'delete'])->name('delete'); // Xử lý xóa series
        Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [SeriesController::class, 'autocomplete'])->name('autocomplete'); // Lấy series theo từ
    });

    Route::prefix('posts')->name('posts.')->group(function () { // Chức năng quản lý bài đăng
        Route::middleware(['canAny:view_declarations'])->get('/index', [PostController::class, 'index'])->name('index'); // Hiển thị danh sách bài đăng
        Route::middleware(['canAny:create_declarations'])->get('/create', [PostController::class, 'create'])->name('create'); // Hiển thị form tạo mới bài đăng
        Route::middleware(['canAny:create_declarations'])->post('/store', [PostController::class, 'store'])->name('store'); // Xử lý thêm mới bài đăng
        Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [PostController::class, 'edit'])->name('edit'); // Hiển thị form sửa bài đăng
        Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [PostController::class, 'update'])->name('update'); // Xử lý sửa bài đăng
        Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [PostController::class, 'delete'])->name('delete'); // Xử lý xóa bài đăng
    });
