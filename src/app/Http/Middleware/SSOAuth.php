<?php

namespace Newtech\SSOBridge\App\Http\Middleware;

use Closure;
use Newtech\SSOBridge\App\Models\User;
use Illuminate\Http\Response;

class SSOAuth
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
        $route_name = $request->route()->getName();
        if($request->session()->has('sso-jwt-' . config('ssobridge.sso.application.id')) && $request->session()->has('sso-jwt-array-' . config('ssobridge.sso.application.id'))) {
            $data = [
                "sso-jwt" => $request->session()->get('sso-jwt-' . config('ssobridge.sso.application.id')),
                "sso-jwt-array" => $request->session()->get('sso-jwt-array-' . config('ssobridge.sso.application.id'))
            ];

            // Validating the session.
            $authenticated = User::authenticate_session($data);
            if($authenticated->status == "success") {

                // Checking the default "access_site" permission.
                if(User::user()->can("default::access_site")) {
                    // Checking for "auth.login" to redirect the user.
                    if($route_name == "auth.login") {
                        return redirect('/');
                    }
                    return $next($request);
                } else {
                    abort(403, "You don't have access to this website.");
                }
            }
        }
        if($route_name == "auth.login") {
            return $next($request);
        }
        $request->session()->flush();
        return redirect('/logout');
    }
}
