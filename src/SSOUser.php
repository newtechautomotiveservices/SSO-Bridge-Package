<?php

namespace Newtech\SSOBridge;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
Use Illuminate\Support\Carbon;

class SSOUser implements UserContract
{

    /**
     * All of the user's attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Create a new generic User object.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {

        //$this->attributes = $attributes;
        $this->attributes['id'] = $attributes['id'];
        $this->attributes['account'] = (object)$attributes['account'];
        $this->attributes['account']->full_name = $attributes['account']->full_name ??ucfirst($attributes['account']['first_name'])." ".ucfirst($attributes['account']['last_name']);
        $this->attributes['username'] = $attributes['username'];
        $this->attributes['store'] = (object)$attributes['store'];
        $this->attributes['display'] = $attributes['display'] ?? ucfirst($attributes['username'])."@".ucfirst($attributes['store']['name']);
        $this->attributes['permissions'] = $attributes['permissions'];
        $this->attributes['entityKey'] = $attributes['entityKey'];
        $this->attributes['possibleUsers'] = [];
        foreach($attributes['possibleUsers'] as $user){
            if(is_array($user)){
                $user['store'] = (object)$user['store'];
            }
            $this->attributes['possibleUsers'][] = (object)$user;
        }
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return '';
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember;
    }

    /**
     * Set the "remember me" token value.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->attributes['remember'] = $value;
        return;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return;
    }

    /**
     * Dynamically access the user's attributes.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Dynamically set an attribute on the user.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Dynamically check if a value is set on the user.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Dynamically unset a value on the user.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    public function cache(){
        \Session::put('sso', array_merge($this->attributes, ['expire' => Carbon::now()->addMinutes(5)->timestamp]));
    }
}
