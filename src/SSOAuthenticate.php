<?php

namespace Newtech\SSOBridge;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;

class SSOAuthenticate extends Authenticate
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request,$guards)
        );
    }

    public function redirectTo($request, $guards = []){
        if(in_array('sso', $guards)){
            if($request->session()->has('sso') && $request->session()->has('sso.id')){
                session()->pull('sso');
                abort(403, 'Acess denied');
            }
            $request->session()->put('sso.authLeft', $request->fullUrl());
            return Str::finish(config('sso.authentication_url'), '/').'remote/'.config('sso.id').'?returnTo="'.base64_encode($request->root()).'"';
        }
        return parent::redirectTo($request);
    }
}
