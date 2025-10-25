<?php

namespace App\Http\Middleware;

use Closure;

class SanitizeRequest
{
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        // Sanitize all fields in the request
        foreach ($input as $key => $value) {
            $sanitizedValue = preg_replace('/[*\'"<>!;%&`]/', '', $value);
            $input[$key] = $sanitizedValue;
        }

        // Update the request with sanitized input
        $request->merge($input);

        return $next($request);
    }
}