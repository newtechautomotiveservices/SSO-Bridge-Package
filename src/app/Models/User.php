<?php

namespace Newtech\SSOBridge\App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Session;

// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

class User extends Model
{

    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'store_number', 'permissions', 'stores'
    ];

    public static function user()
    {

        $array = collect(Session::get('sso-jwt-array-' . config('ssobridge.sso.application.id')))->toArray();
        $user = new User($array);
        return $user;
    }

    // For checking the users permissions.
    public function can($permission) {
        if(User::user()->permissions->where('identifier', '=', $permission)->first()) {
            return true;
        } else {
            return false;
        }
    }

    // Authenticating the users JWT.
    public static function authenticate_session($data)
    {
        $jwt = $data["sso-jwt"];
        // Try Catch to detect if the project is set up properly.
        try {
            $session_url = url('/');
            $client = new Client();
            $request = $client->get(config('ssobridge.sso.authentication_url') . "api/remote/user/checkJWT/" . config('ssobridge.sso.application.id') . "/" . $jwt);
            $result = json_decode($request->getBody()->getContents());
            return $result;
        } catch (Exception $e) {
            return "Project not set up properly";
        }
    }

    /* ----------------- MUTATIONS ----------------- */
    public function getPermissionsAttribute($value)
    {
        return collect(json_decode($value, true));
    }
    public function getRolesAttribute($value)
    {
        return collect(json_decode($value, true));
    }
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

    public function getNameAttribute($value)
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
