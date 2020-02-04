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
      $session_url = config('app.debug') ? "DEBUG::" . route('api.passSession.dev', "") : route('api.passSession');
      $client = new Client();
      $request = $client->post(config('ssobridge.sso.authentication_url') . "api/remote/user/requestAuthRoute", [
          'form_params' => [
              'session_url' => $session_url,
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

  public function indexLogout() {
    session()->flush();
    return redirect(config('ssobridge.sso.application.login_route'));
  }

  public function pass_session (Request $request) {
    $user = User::where('remote_id', '=', $request['user']['remote_id'])->first();
    if($user) {
        $user->update($request['user']);
    } else {
        $user = User::create($request['user']);
    }

    $url = \URL::temporarySignedRoute('signed.remote.putSession', now()->addMinutes(60), [
      "id" => $request['user']['remote_id'],
      "token" => $request['user']['token']
    ]);

    return [
      "status" => "success",
      "data" => [
        "url" => url(config('ssobridge.sso.application.home_route'))
      ]
    ];
  }

  public function pass_session_dev (Request $request, $json) {
    $json = json_decode(base64_decode($json), true);
    $user = User::where('remote_id', '=', $json['remote_id'])->first();
    if($user) {
      $user->update($json);
    } else {
      $userTMP = User::create($json);
    }
    $request->session()->put([
      "_identifier(" . config('ssobridge.sso.application.id') . ")" => $json['remote_id'],
      "_session_token(" . config('ssobridge.sso.application.id') . ")" => $json['token']
    ]);
    return redirect(config('ssobridge.sso.application.home_route'));
  }

  public function putSession (Request $request) {
    $request->session()->put([
      "_identifier(" . config('ssobridge.sso.application.id') . ")" => $request['id'],
      "_session_token(" . config('ssobridge.sso.application.id') . ")" => $request['token']
    ]);
    return redirect(config('ssobridge.sso.application.home_route'));
  }
}
