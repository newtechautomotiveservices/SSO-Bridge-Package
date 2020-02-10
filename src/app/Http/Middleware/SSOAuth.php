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
//        return $next($request);
        $route_name = $request->route()->getName();
        if($request->session()->has('sso-jwt') && $request->session()->has('sso-jwt-array')) {
            $data = [
                "sso-jwt" => $request->session()->get('sso-jwt'),
                "sso-jwt-array" => $request->session()->get('sso-jwt-array')
            ];
            $authenticated = User::authenticate_session($data);
            if($authenticated->status == "success") {
                if($route_name == "auth.login") {
                    return redirect('/');
                }
                return $next($request);
            }
        }
        if($route_name == "auth.login") {
            return $next($request);
        }
        $request->session()->flush();
        return redirect('/logout');
    }
}
