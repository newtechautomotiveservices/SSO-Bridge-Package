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
        if($request->session()->has("_identifier(" . config('ssobridge.sso.application.id') . ")") && $request->session()->has("_session_token(" . config('ssobridge.sso.application.id') . ")")) {
            $data = [
                "identifier" => $request->session()->get("_identifier(" . config('ssobridge.sso.application.id') . ")"),
                "session_token" => $request->session()->get("_session_token(" . config('ssobridge.sso.application.id') . ")")
            ];
            $authenticated = User::user_authenticate_session($data);
            if($authenticated['status'] == "success") {
                $request->session()->put([
                    "_identifier(" . config('ssobridge.sso.application.id') . ")" => $authenticated['data']['id'],
                    "_session_token(" . config('ssobridge.sso.application.id') . ")" => $authenticated['data']['token']
                ]);
                if (User::user()->can("default::access_site")) {
                    if($route_name == "sso.auth.login") {
                        return redirect(config('ssobridge.sso.home_route'));
                    }
                    return $next($request);
                } else {
                    abort(403, 'You dont have access to this site.');
                }
            }
        }
        if($route_name == "sso.auth.login") {
            return $next($request);
        }
        $request->session()->flush();
        return redirect(config('ssobridge.sso.logout_route'));
    }
}
