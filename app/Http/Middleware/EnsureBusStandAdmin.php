<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusStandAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isBusStandAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bus Stand Admin access required.'], 403);
            }

            abort(403, 'This area is for Bus Stand Admin accounts (vehicles, routes, schedules).');
        }

        return $next($request);
    }
}
