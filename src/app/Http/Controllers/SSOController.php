<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class SSOController extends Controller
{
    // Route: Setting the token.
    public function get_setToken ($token) {
        $jwt_explode = explode('.', $token);
        if(count($jwt_explode) == 3){
            $signature = hash_hmac('sha256', $jwt_explode[0]. "." . $jwt_explode[1], config('sso.token'), false);
            if($signature == $jwt_explode[2]){
                $perms = $this->decode($jwt_explode[1]);
                if(!$perms){
                    abort(404, 'Not Found');
                }
                $perms['expire'] = $this->decode($jwt_explode[0])['exp'] ?? Carbon::now()->timestamp;
                if(Session::has('sso.authLeft')){
                    $prevPath = Session::get('sso.authLeft');
                }else{
                    $prevPath = parse_url(Session::get('_previous')[0] ?? '/', PHP_URL_PATH);
                }
                Session::put(['sso' => $perms]);
                return redirect($prevPath);
            }
            abort(403, 'Unauthorzed');
        }
        abort(404, 'Not Found');
    }

    public function logout(){
        $response = Http::post(Str::finish(config('sso.authentication_url'), '/').'api/appControl/logout', [
            'token' => request()->user()->account->remember,
            'account' => request()->user()->account->id
        ]);
        if($response->successful()){
            return $this->refreshPermissions();
        }
        return back();
    }

    public function refreshPermissions(){
        Session::pull('sso');
        return back();
    }

    public function changeUser($id){
        $response = Http::post(Str::finish(config('sso.authentication_url'), '/').'api/appControl/userchange', [
            'app_id' => config('sso.id'),
            'token' => config('sso.token'),
            'account' => request()->user()->account->id,
            'user_id' => $id
        ]);
        if($response->successful()){
            return $this->refreshPermissions();
        }
        return back();
    }

    private function decode($token){
        return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+', $token))), true);
    }

    // URL Encode base64, this needs to be moved to a helper.
    public static function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}
