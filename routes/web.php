<?php

include('admin.php');

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
    });
});
