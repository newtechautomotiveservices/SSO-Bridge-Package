<?php

namespace Newtech\SSOAuth\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

use Session;

use Illuminate\Http\Response;

// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

class User extends Model
{
    protected $table = 'sso_users';

    protected $fillable = [
        'id', 'first_name', 'last_name', 'avatar', 'email', 'known_logins', 'remote_token', 'store_number', 'guards'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function user()
    {
        $user_id = session()->get("_user_id");
        return User::find($user_id);
    }

    public static function authenticate ($email, $password) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => config('ssoauth.main.sso_url') . "/api/ssoauth/authenticate",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n" . $email . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n" . $password . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"product_id\"\r\n\r\n" . config('ssoauth.main.product_id') . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer a34e5206-0d5b-4250-a901-ddea650dcd0c",
            "Postman-Token: e3c15764-c8a9-4f63-956e-46dd3b51cb9e",
            "cache-control: no-cache",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
          ),
        ));

        $response = curl_exec($curl);
        $formatted_response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        if($formatted_response->status == "success") {
            $user = User::find($formatted_response->output->id);
            if($user) {
                $user->update(json_decode(curl_exec($curl), true)["output"]);
            } else {
                $user = User::create(json_decode(curl_exec($curl), true)["output"]);
            }
            curl_close($curl);

            session()->put('_user_id', $user->id);
            session()->put('_user_token', $user->remote_token);
            return "true";
        } else {
            curl_close($curl);
            session()->flush();
            return "false";
        }
    }


    /* ----------------- MUTATIONS ----------------- */
    public function getActiveStoreAttribute() {
        $active_store = $this->guards['stores'];
        foreach ($active_store as $index => $active_store) {
            if($active_store['store_number'] == $this->store_number) {
                return $active_store;
            }
        }
        return false;
    }

    public function getNameAttribute($value)
    {
       return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getPermissionsAttribute($value)
    {
        $permissions = json_decode($this->guards)->permissions;
        return $permissions;
    }

    public function getRolesAttribute($value)
    {
        $roles = json_decode($this->guards)->roles;
        return $roles;
    }

    public function can($permission_name)
    {
        foreach ($this->permissions as $index => $permission) {
            if(strtolower($permission) == strtolower($permission_name)) {
                return true;
            }
        }
        return false;
    }

    public static function verify ($token, $user_id) {
        $user = User::find($user_id);
        if($user) {
            if($token == $user->remote_token) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
