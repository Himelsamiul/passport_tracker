<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**
         * ------------------------------------------------------------
         * Custom Middleware Aliases
         * ------------------------------------------------------------
         * You can define your own middleware here.
         * Example: 'auth.session' checks if the user session exists.
         */
        $middleware->alias([
            'auth.session' => \App\Http\Middleware\SessionAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // You can customize how exceptions are handled here
        // (keep default if you don't need any change)
    })
    ->create();
