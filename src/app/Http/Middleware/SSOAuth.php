<?php

namespace Newtech\SSOBridge\Middleware;

use Closure;

use Newtech\SSOBridge\Models\User;

class SSOAuthCheck
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
        $token = $request->session()->get('_user_token');
        $user_id = $request->session()->get('_user_id');
        $verify = User::verify($token, $user_id);
        if($verify) {
            $user = User::find($user_id);
            if($user->can("access site")) {
                return $next($request);
            } else {
                abort(403, 'You dont have access to this site.');
            }

        } else {
            $request->session()->flush();
            return redirect(config('ssobridge.main.login_route'));
        }
    }

    private function checkToken($request) {

    }
}
