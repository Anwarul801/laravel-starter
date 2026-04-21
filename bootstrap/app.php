<?php
/**
 * @Author: Anwarul
 * @Date: 2025-11-17 14:53:56
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-15 12:24:59
 * @Description: Innova IT
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // ✅ API routes added
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ API middleware group (Sanctum)
        $middleware->api([
           \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
           \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ✅ Middleware aliases (Spatie + custom)
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            // 'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
