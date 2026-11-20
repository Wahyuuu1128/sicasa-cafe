<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Arahkan user guest yang belum login ke /sistem
        $middleware->redirectTo(
            guests: '/sistem',
            users: '/dashboard'
        );

        // Daftarkan alias middleware role kita di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback', // Biarkan Midtrans masuk tanpa token CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    
    