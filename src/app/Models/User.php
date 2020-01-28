<?php

namespace Newtech\SSOBridge\App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

class User extends Model
{
    protected $table = 'sso_users';

    protected $fillable = [
        'remote_id', 'token', 'first_name', 'last_name', 'email', 'store_number', 'roles', 'permissions', 'stores'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function user()
    {
        $user_id = session()->get("_identifier(" . config('ssobridge.sso.application.id') . ")");
        $user_token = session()->get("_session_token(" . config('ssobridge.sso.application.id') . ")");
        return User::where('remote_id', '=', $user_id)->where('token', '=', $user_token)->first();
    }

    public function can($permission) {
        if(User::user()->permissions->where('identifier', '=', $permission)->first()) {
            return true;
        } else {
            return false;
        }
    }

    public static function user_authenticate_session($data)
    {
        $identifier = $data['identifier'];
        $session_token = $data['session_token'];
        $user = User::where('remote_id', '=', $identifier)->where('token', '=', $session_token)->first();
        // Check if the id matches a users identifier.
        if($user && $user->token == $session_token) {
            return [
                'status' => 'success',
                'data' => $user->toArray()
            ];
        } else {
            return [
                'status' => 'failure',
                'code' => 'invalid_session'
            ];
        }
    }

    public function getPermissionsAttribute($value)
    {
        return collect(json_decode($value, true));
    }
    public function getRolesAttribute($value)
    {
        return collect(json_decode($value, true));
    }

    /* ----------------- MUTATIONS ----------------- */
    public function getActiveStoreAttribute()
    {
        $active_store = $this->guards['stores'];
        foreach ($active_store as $index => $active_store) {
            if ($active_store['store_number'] == $this->store_number) {
                return $active_store;
            }
        }
        return false;
    }
    public function getStoresAttribute($value)
    {
        return collect(json_decode($value, true));
    }

    // public function getNameAttribute($value)
    // {
    //     return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    // }

    // public function getPermissionsAttribute($value)
    // {
    //     $permissions = json_decode($this->guards)->permissions;
    //     return $permissions;
    // }

    // public function getRolesAttribute($value)
    // {
    //     $roles = json_decode($this->guards)->roles;
    //     return $roles;
    // }

    // public function can($permission_name)
    // {
    //     foreach ($this->permissions as $index => $permission) {
    //         if (strtolower($permission) == (strtolower($permission_name) || strtolower(config('ssobridge.sso.super_admin_identifier')))) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }

    // public function getStoresAttribute($value)
    // {
    //     return json_decode($this->guards)->stores;
    // }
}
