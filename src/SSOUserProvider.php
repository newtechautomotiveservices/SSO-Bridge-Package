<?php

namespace Newtech\SSOBridge;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;

Use Illuminate\Support\Facades\Http;

class SSOUserProvider implements UserProvider
{
    /**
     * Create a new apiv2 user provider.
     *
     * @return void
     */
    public function __construct()
    { }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return new SSOUser(request()->session()->get('sso'));
    }

     /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials($credentials)
    {
        
        return null;
    }

     /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $response = Http::get(\Str::finish(config('sso.authentication_url'), '/').'api/appControl/permissions/'.config('sso.id').'/'.$identifier.'/'.$token);
        if($response->successful()){
            return new SSOUser($response->json());
        }else{
            \Cookie::Queue(\Cookie::forget('ssoAuth'));
        }
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        return $user->setRememberToken($token);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }
}
