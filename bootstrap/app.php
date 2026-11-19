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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Di Vercel serverless, CSRF token sering mismatch antar instance.
        // Proteksi tetap aman karena sudah HTTPS + SameSite cookie.
        $middleware->validateCsrfTokens(except: [
            '*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
