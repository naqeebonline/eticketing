<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetThemeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $theme = $request->user()?->theme
            ?? $request->cookie('theme')
            ?? session('theme', 'light');

        view()->share('currentTheme', $theme);

        return $next($request);
    }
}
