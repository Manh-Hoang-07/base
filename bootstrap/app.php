<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\Authenticate;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load API routes FIRST (before web routes)

            // Load main API routes (no auth required)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Load API routes with web middleware (no auth required)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/web.php'));

            // Load API admin routes (no auth for testing)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/admin.php'));

            // Load API console routes with auth
            Route::middleware(['api', 'auth:sanctum', 'admin'])
                ->prefix('api')
                ->group(base_path('routes/api/console.php'));

            // Admin routes removed - using SPA only
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'admin' => AdminMiddleware::class,
            'canAny' => \App\Http\Middleware\BypassPermissions::class, // Bypass permissions for development
            'api.auth' => ApiAuthMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) { //NOSONAR
        //
    })->create();
