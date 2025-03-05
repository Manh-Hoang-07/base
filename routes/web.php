<?php

include('admin.php');

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;


Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('loginForm'); // Hiển thị form login
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login'); // Xử lý login
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout'); // Xử lý đăng xuất

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('registerForm'); // Hiển thị form đăng ký
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register'); // Xử lý đăng ký


Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.home.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () { // Chức năng quản lý tài khoản
        Route::middleware(['canAny:view_users'])->get('/index', [UserController::class, 'index'])->name('index'); // Hiển thị danh sách tài khoản
        Route::middleware(['canAny:create_users'])->get('/create', [UserController::class, 'create'])->name('create'); // Hiển thị form tạo tài khoản
        Route::middleware(['canAny:create_users'])->post('/store', [UserController::class, 'store'])->name('store'); // Xử lý tạo tài khoản
        Route::middleware(['canAny:edit_users'])->get('/edit/{id}', [UserController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa
        Route::middleware(['canAny:edit_users'])->post('/update/{id}', [UserController::class, 'update'])->name('update'); // Xử lý chỉnh sửa
        Route::middleware(['canAny:delete_users'])->post('/delete/{id}', [UserController::class, 'destroy'])->name('delete'); // Xử lý xóa
        // 🚀 Hiển thị giao diện phân vai trò
        Route::middleware(['canAny:assign_users'])->get('/assign-roles/{id}', [UserController::class, 'showAssignRolesForm'])->name('showAssignRolesForm');
        // 🚀 Xử lý gán vai trò cho người dùng
        Route::middleware(['canAny:assign_users'])->post('/assign-roles/{id}', [UserController::class, 'assignRoles'])->name('assignRoles');
    });

    Route::prefix('roles')->name('roles.')->group(function () { // Chức năng quản lý vai trò
        Route::middleware(['canAny:view_roles'])->get('/index', [RoleController::class, 'index'])->name('index'); // Hiển thị danh sách vai trò
        Route::middleware(['canAny:create_roles'])->get('/create', [RoleController::class, 'create'])->name('create'); // Hiển thị form tạo mới vai trò
        Route::middleware(['canAny:create_roles'])->post('/store', [RoleController::class, 'store'])->name('store'); // Xử lý thêm mới vai trò
        Route::middleware(['canAny:edit_roles'])->get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::middleware(['canAny:edit_roles'])->post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_roles'])->delete('/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () { // Chức năng quản lý quyền
        Route::middleware(['canAny:view_permissions'])->get('/index', [PermissionController::class, 'index'])->name('index'); // Hiển thị danh sách quyền
        Route::middleware(['canAny:create_permissions'])->get('/create', [PermissionController::class, 'create'])->name('create'); // Hiển thị form tạo mới quyền
        Route::middleware(['canAny:create_permissions'])->post('/store', [PermissionController::class, 'store'])->name('store'); // Xử lý thêm mới quyền
        Route::middleware(['canAny:edit_permissions'])->get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit'); // Hiển thị form sửa quyền
        Route::middleware(['canAny:edit_permissions'])->post('/update/{id}', [PermissionController::class, 'update'])->name('update'); // Xử lý sửa quyền
        Route::middleware(['canAny:delete_permissions'])->delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('delete'); // Xử lý xóa quyền
    });
});


//Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//    Route::resource('users', UserController::class);
//    Route::resource('roles', RoleController::class);
//});
