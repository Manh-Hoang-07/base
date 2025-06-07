<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Console Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for console/command operations.
| These routes are typically used for administrative tasks, monitoring,
| and system operations that require special authentication.
|
*/

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1/console')->name('api.console.')->group(function () {
    
    // System monitoring routes
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/health', function () {
            return response()->json([
                'status' => 'healthy',
                'timestamp' => now(),
                'version' => config('app.version', '1.0.0')
            ]);
        })->name('health');
        
        Route::get('/info', function () {
            return response()->json([
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale')
            ]);
        })->name('info');
    });

    // Cache management routes
    Route::prefix('cache')->name('cache.')->group(function () {
        Route::post('/clear', function () {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            return response()->json(['message' => 'Cache cleared successfully']);
        })->name('clear');
        
        Route::post('/config-clear', function () {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            return response()->json(['message' => 'Config cache cleared successfully']);
        })->name('config.clear');
        
        Route::post('/view-clear', function () {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return response()->json(['message' => 'View cache cleared successfully']);
        })->name('view.clear');
    });

    // Database operations
    Route::prefix('database')->name('database.')->group(function () {
        Route::get('/status', function () {
            try {
                \Illuminate\Support\Facades\DB::connection()->getPdo();
                return response()->json(['status' => 'connected']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'disconnected', 'error' => $e->getMessage()], 500);
            }
        })->name('status');
    });
});
