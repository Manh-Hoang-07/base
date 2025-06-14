<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Load admin API routes
require __DIR__ . '/api/admin.php';

// Load web API routes
require __DIR__ . '/api/web.php';
