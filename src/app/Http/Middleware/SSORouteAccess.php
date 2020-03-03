<?php

namespace Newtech\SSOBridge\App\Http\Middleware;

use Closure;
use \Illuminate\Support\Facades\Gate;

class SSORouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$action)
    {
        if(Gate::authorize('sso', $action)){
            return $next($request);
        }
        abort(403, "Not authorized to view this page");
    }
}
