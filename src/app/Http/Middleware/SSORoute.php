<?php

namespace Newtech\SSOBridge\Middleware;

use Closure;

use Newtech\SSOBridge\Models\User;

class SSORouteCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::user();
        if($user) {
            if($user->can("access " . $request->route()->getName())) {
                return $next($request);
            } else {
                abort(403, 'You dont have access to this page.');
            }
        } else {
            $request->session()->flush();
            return redirect(config('ssobridge.main.login_route'));
        }
    }
}
