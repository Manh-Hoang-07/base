<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel is working!',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

// Vue test route
Route::get('/vue-debug', function () {
    return view('spa')->with([
        'debug' => true,
        'test_mode' => true
    ]);
});

// Auth routes removed - using SPA API only

// Upload route
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');

// Vue SPA Route - Serve SPA for all other routes (exclude API routes)
Route::get('/{any?}', function () {
    return view('spa');
})->where('any', '^(?!api).*')->name('spa');

