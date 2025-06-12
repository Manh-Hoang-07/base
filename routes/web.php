<?php

include_once('admin.php');

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Home\Posts\PostController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel is working!',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

// Vue SPA Route - Serve SPA for main routes
Route::get('/', function () {
    return view('spa');
})->name('spa.home');

// Vue SPA Route - Catch all routes and serve SPA
Route::get('/{any}', function () {
    return view('spa');
})->where('any', '^(?!api|login|register|logout|auth|upload|test).*$')->name('spa');

// Keep existing API and auth routes below...

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login.index'); // Hiển thị form login
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login'); // Xử lý login
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout'); // Xử lý đăng xuất
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login'); // Hiển thị form đăng nhập với google
Route::get('/auth/google/callback', [GoogleController::class, 'callback']); // Xử lý đăng nhập với google

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register.index'); // Hiển thị form đăng ký
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register'); // Xử lý đăng ký
Route::post('/send-otp-register', [RegisterController::class, 'sendOtp'])->name('send.register'); // Gửi OTP đăng ký tài khoản

Route::get('/forgot-password', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('forgot.password.index'); // Hiển thị form quên mật khẩu
Route::post('/send-otp-forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('send.forgot.password'); // Gửi OTP quên mật khẩu
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password'); // Xử lý tạo lại mật khẩu

Route::get('/', [PostController::class, 'index'])->name('home');
// Routes cho phần trang chủ (không yêu cầu đăng nhập)
Route::prefix('home')->name('home.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('home');
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
    });
});



Route::post('/upload', [UploadController::class, 'upload'])->name('upload');

