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
        $store = Array(
            'name' => config('sso.faker.store.name', 'Faker Enterprises LLC.'),
            'store_number' => config('sso.faker.store.number', '15212'),
        );
        $user = Array(
            'id' => 0,
            'display' => \Str::title(config('sso.faker.username', 'faker')).'@'.$store['name'],
            'store' => $store
        );

        $user = Array(
            'id' => $user['id'],
            'username' => config('sso.faker.username', 'faker'),
            'entityKey' => config('faker.sso.entityKey', 42),
            'display' => $user['display'],
            'account' => [
                'id' => 0,
                'first_name' => config('sso.faker.first_name', 'Faker'),
                'last_name' => config('sso.faker.last_name', 'McFakerson'),
                'email' => config('sso.faker.email', 'faker@fakerllc.com'),
                'default_user' => 0
            ],
            'store' => $store,
            'permissions' => config('sso.faker.permissions', ['default::access_site']),
            'possibleUsers' => [$user]
        );

        return new SSOUser($user);
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
