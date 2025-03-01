<?php

include('admin.php');

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'loginForm'])->name('loginForm'); // Hiển thị form login
Route::post('/login', [AuthController::class, 'login'])->name('login'); // Xử lý login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Xử lý đăng xuất

Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm'); // Hiển thị form đăng ký
Route::post('/register', [AuthController::class, 'register'])->name('register'); // Xử lý đăng ký


Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
