<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

$routes = [
    'routes/Products/products.php',
];

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () use ($routes) {
            foreach ($routes as $route) {
                Route::prefix('api')
                    ->middleware('api')
                    ->middleware('auth:api') // 🔥 aquí cambiamos
                    ->group(base_path($route));
            }

        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth:api' => \App\Http\Middleware\ApiAuthenticate::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
