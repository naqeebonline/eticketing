<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTerminalAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isTerminalAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Terminal / Adda Admin access required.'], 403);
            }

            abort(403, 'This area is for Terminal / Adda Admin accounts.');
        }

        return $next($request);
    }
}
