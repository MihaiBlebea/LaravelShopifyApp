<?php

namespace App\Http\Middleware;

use Closure;

class FrameHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        // TODO This middleware can be removed. Don't forget to remove from Kernel and from routes
        $response = $next($request);
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        return $response;
        // return $next($request);
    }
}
