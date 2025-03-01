<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', [LoginController::class, 'index'])
    ->name('admin.login');
Route::get('/admin/register', [RegisterController::class, 'index'])
    ->name('admin.register');

Route::post('/admin/login', [LoginController::class, 'authenticate'])
    ->name('admin.login.authenticate');
