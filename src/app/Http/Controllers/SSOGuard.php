<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;



class SSOGuard implements Guard
{
    use \Illuminate\Auth\GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;


    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $inputKey
     * @return void
     */
    public function __construct(
        UserProvider $provider,
        Request $request, 
        $userKey = 'email', 
        $inputKey = 'paswword')
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->userKey = $userKey;
        $this->inputKey = $inputKey;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }
        
        $user = null;
        $user = $this->provider->retrieveById('');
        return $this->user = $user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        return null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
