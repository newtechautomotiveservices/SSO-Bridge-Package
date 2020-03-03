<?php

namespace Newtech\SSOBridge\App\Http\Middleware;

use \Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class SSOAuthenticate extends Authenticate
{
    public function redirectTo($request){
        if($request->session()->has('sso') && $request->session()->has('sso.id')){
            abort(403, 'Acess denied');
        }
        $request->session()->put('sso.authLeft', $request->fullUrl());
        return config('sso.authentication_url').'/remote/'.config('sso.id').'?returnTo="'.base64_encode($request->root()).'"';
    }
}
