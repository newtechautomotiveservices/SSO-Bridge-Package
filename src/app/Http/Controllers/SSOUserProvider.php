<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Carbon;

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
        if(Session::has('sso') && Session::has('sso.id')){
            if(Session::get('sso.expire') < Carbon::now()->timestamp){
                Session::flush();
                return null;
            }
            if(in_array('default::access_site', Session::get('sso.permissions'))){
                return new SSOUser(request()->session()->get('sso'));
            }
        }
        return null;
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
        return;
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