<?php

namespace Newtech\SSOBridge\App\Http\Middleware;

use \Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class SSOAuthenticate extends Authenticate
{
    public function redirectTo($request){
        $request->session()->put('sso.authLeft', $request->fullUrl());
        return config('ssobridge.sso.authentication_url').'/remote/'.config('ssobridge.sso.id').'?returnTo="'.base64_encode($request->root()).'"';
    }
}
