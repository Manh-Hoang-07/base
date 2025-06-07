<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAnyPermission;
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
            // Load API routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/web.php'));

            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('api')
                ->group(base_path('routes/api/admin.php'));

            Route::middleware(['api', 'auth:sanctum', 'admin'])
                ->prefix('api')
                ->group(base_path('routes/api/console.php'));

            // Load admin routes
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'admin' => AdminMiddleware::class,
            'canAny' => CheckAnyPermission::class,
            'api.auth' => ApiAuthMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) { //NOSONAR
        //
    })->create();
