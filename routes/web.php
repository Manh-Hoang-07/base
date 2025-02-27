<?php

include('admin.php');

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'loginForm'])->name('loginForm'); // Hiển thị form login
Route::post('/login', [AuthController::class, 'login'])->name('login'); // Xử lý login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
