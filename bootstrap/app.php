<?php

use App\Http\Middleware\EnsureBusStandAdmin;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureTerminalAdmin;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\SetThemeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'super_admin' => EnsureSuperAdmin::class,
            'bus_stand_admin' => EnsureBusStandAdmin::class,
            'terminal_admin' => EnsureTerminalAdmin::class,
            'locale' => LocaleMiddleware::class,
            'theme' => SetThemeMiddleware::class,
        ]);

        $middleware->web(append: [
            LocaleMiddleware::class,
            SetThemeMiddleware::class,
        ]);

        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
