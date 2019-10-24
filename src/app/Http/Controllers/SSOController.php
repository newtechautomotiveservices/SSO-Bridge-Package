<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newtech\SSOBridge\App\Models\User;

class SSOController extends Controller
{
    // Login Page
    public function indexLogin()
    {
        // Try Catch to detect if the project is set up properly.
        try {
            $session_url = config('app.debug') ? "debug^:" . route('api.passSession') : route('api.passSession');
            $curl        = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL            => config("ssobridge.sso.sso_url") . "/api/ssoauth/authenticate",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"session_url\"\r\n\r\n" . $session_url . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"product_id\"\r\n\r\n" . config('ssobridge.sso.product_id') . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Bearer a34e5206-0d5b-4250-a901-ddea650dcd0c",
                    "Postman-Token: b54ece01-19e6-4ac7-8a4d-62fccc0aa245",
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                ),
            ));
            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);
            return redirect($response);
        } catch (Exception $e) {
            return "Project not set up properly";
        }
    }

    public function indexLogout()
    {
        session()->flush();
        return redirect(config('ssobridge.sso.login_route'));
    }

    // Ajax & Posts
    public function request_pass_session(Request $request)
    {
        return URL::temporarySignedRoute(
            'signed.pass_session', now()->addMinutes(30), ['user' => $request['user']]
        );
    }

    // Passing the session data
    public function pass_session(Request $request)
    {
        $user = User::find($request["user"]["id"]);
        if ($user) {
            $user->update($request['user']);
        } else {
            $user = User::create($request['user']);
            session()->put('_user_id', $user->id);
            session()->put('_user_token', $user->remote_token);
            return redirect(config('ssobridge.sso.home_route'));
        }
        session()->put('_user_id', $user->id);
        session()->put('_user_token', $user->remote_token);
        return redirect(config('ssobridge.sso.home_route'));
    }

    // Passing the session data while running the project locally.
    public function pass_session_dev($json)
    {
        $data = json_decode(base64_decode($json), true);
        $user = User::find($data["id"]);
        if ($user) {
            $user->update($data);
            session()->put('_user_id', $user->id);
            session()->put('_user_token', $user->remote_token);
            return redirect(config('ssobridge.sso.home_route'));
        } else {
            $userTMP = User::create($data);
            session()->put('_user_id', $userTMP->id);
            session()->put('_user_token', $userTMP->remote_token);
            return redirect(config('ssobridge.sso.home_route'));
        }
    }
}
