<?php

namespace Newtech\SSOBridge\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Newtech\SSOBridge\App\Models\User;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SSOController extends Controller
{
  // Login Page
  public function indexLogin() {
    // Try Catch to detect if the project is set up properly.
    try {
      $session_url = config('app.debug') ? "debug^:" . route('api.passSession') : route('api.passSession');

      $client = new Client();
      $request = $client->post("https://ssodev.newtechautomotiveservices.com/api/ssoauth/authenticate", [
          'form_params' => [
              'session_url' => $session_url,
              'product_id' => config('ssobridge.sso.product_id'),
              'product_token' => config('ssobridge.sso.product_token')
          ]
      ]);
      $result = json_decode($request->getBody()->getContents());
      if($result->status == "failure") {
        return $result->message;
      }
      return redirect($result->url);
    } catch (Exception $e) {
      return "Project not set up properly";
    }
  }

  public function indexLogout() {
    session()->flush();
    return redirect(config('ssobridge.sso.login_route'));
  }


  // Ajax & Posts
  public function request_pass_session(Request $request) {
    return URL::temporarySignedRoute(
        'signed.pass_session', now()->addMinutes(30), ['user' => $request['user']]
    );
  }

  public function pass_session (Request $request) {
    $user = User::find($request["user"]["id"]);
    if($user) {
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

  public function pass_session_dev ($json) {
    $data = json_decode(base64_decode($json), true);
    $user = User::find($data["id"]);
    if($user) {
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
