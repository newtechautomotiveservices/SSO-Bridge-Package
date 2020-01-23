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
      $session_url = config('app.debug') ? "DEBUG::" . route('api.passSession.dev') : route('api.passSession');
      $client = new Client();
      $request = $client->post(config('ssobridge.sso.authentication_url') . "api/ssoauth/authenticate", [
          'form_params' => [
              'session_url' => $session_url,
              'application_id' => config('ssobridge.sso.application_id'),
              'application_token' => config('ssobridge.sso.application_token')
          ]
      ]);
      $result = json_decode($request->getBody()->getContents());
      if($result->status == "failure") {
        return $result->message;
      }
      return redirect($result->data->url);
    } catch (Exception $e) {
      return "Project not set up properly";
    }
  }

  public function indexLogout() {
    session()->flush();
    return redirect(config('ssobridge.sso.login_route'));
  }

  public function pass_session (Request $request) {
    $user = User::find($request["user"]["id"]);
    if($user) {
        $user->update($request['user']);
    } else {
        $user = User::create($request['user']);
        $request->session()->put([
          "_identifier(" . config('ssobridge.sso.application_id') . ")" => $user->id,
          "_session_token(" . config('ssobridge.sso.application_id') . ")" => $user->session->token
        ]);
        return redirect(config('ssobridge.sso.home_route'));
    }
    session()->put("_identifier(" . config('ssobridge.sso.application_id') . ")", $user->id);
    session()->put("_session_token(" . config('ssobridge.sso.application_id') . ")", $user->session->token);
    return redirect(config('ssobridge.sso.home_route'));
  }

  public function pass_session_dev ($json) {
    $data = json_decode(base64_decode($json), true);
    $user = User::find($data["id"]);
    if($user) {
      $user->update($data);
      session()->put("_identifier(" . config('ssobridge.sso.application_id') . ")", $user->id);
      session()->put("_session_token(" . config('ssobridge.sso.application_id') . ")", $user->session->token);
      return redirect(config('ssobridge.sso.home_route'));
    } else {
      $userTMP = User::create($data);
      session()->put("_identifier(" . config('ssobridge.sso.application_id') . ")", $userTMP->id);
      session()->put("_session_token(" . config('ssobridge.sso.application_id') . ")", $user->session->token);
      return redirect(config('ssobridge.sso.home_route'));
    }
  }
}