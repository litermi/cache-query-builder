<?php

namespace Litermi\Cache\Middlewares;

use Closure;

/**
 *
 */
class RemoveDisableQueryHeaderMiddleware
{
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $request->headers->remove('j0ic3-disable-4ZZm4uG-0a7P1-query-PiEcPBU');
        return $next($request);
    }
}





