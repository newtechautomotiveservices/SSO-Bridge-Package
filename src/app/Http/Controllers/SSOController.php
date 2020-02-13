<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Newtech\SSOBridge\App\Models\User;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SSOController extends Controller
{
    // Route: Login Page
    public function indexLogin() {
        // Try Catch to detect if the project is set up properly.
        try {
          $session_url = url('/');
          $client = new Client();
          $request = $client->post(config('ssobridge.sso.authentication_url') . "api/remote/user/requestAuthRoute", [
              'form_params' => [
                  'session_url' => $session_url,
                  'home_route' => config('ssobridge.sso.application.home_route'),
                  'logout_route' => config('ssobridge.sso.application.logout_route'),
                  'application_id' => config('ssobridge.sso.application.id'),
                  'application_token' => config('ssobridge.sso.application.token')
              ]
          ]);
          $result = json_decode($request->getBody()->getContents());

          if($result->status == "failure") {
            dd($result);
            return $result->message;
          }
          return redirect($result->data->url);
        } catch (Exception $e) {
          return "Project not set up properly";
        }
    }

    // Route: Logout Page
    public function indexLogout() {
        session()->flush();
        return redirect(config('ssobridge.sso.application.login_route'));
    }

    // Route: Setting the token.
    public function get_setToken ($token) {
        $jwt_token = ".b4a~^?y}C8A@fRDHPipXtY(y?GAzvsppDk.9+<.X§9o/K2vRmS8(t3UTweC#a5FsT+=%AYw<0rKo#ZG=_<gY7cm6qMVs>8NWA^{c)5DVDSN-yL%4ch9>~OJ+3)GF3°#";
        $jwt_explode = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
        \Illuminate\Support\Facades\Session::put("sso-jwt-" . config('ssobridge.sso.application.id'), $token);
        \Illuminate\Support\Facades\Session::put("sso-jwt-array-" . config('ssobridge.sso.application.id'), $jwt_explode);
        return redirect(config('ssobridge.sso.application.home_route'));
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
