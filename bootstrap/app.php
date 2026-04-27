<?php

// ============================================================
// bootstrap/app.php — Laravel 12 style
// ============================================================

use App\Http\Middleware\RoleMiddleware;
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

        // Alias middleware untuk role check
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Middleware web group tambahan
        $middleware->web(append: [
            // tambahan global web middleware jika perlu
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Render 403 ke view custom jika ada
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }
            return response()->view('errors.403', ['message' => $e->getMessage()], 403);
        });

    })
    ->create();
