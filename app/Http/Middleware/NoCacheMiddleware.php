<?php

namespace App\Http\Middleware;

use Closure;

class NoCacheMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Sat, 01 Jan 1970 00:00:00 GMT');

        return $response;
    }
}