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

  public function indexLogout() {
    session()->flush();
    return redirect(config('ssobridge.sso.application.login_route'));
  }
}
