<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.sso.application.login_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogin')->name('sso.auth.login');
	Route::get(config('ssobridge.sso.application.logout_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogout')->name('sso.auth.logout');
    Route::get('/ssobridge/setToken/{token}', function ($token) {
        $jwt_explode = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
        \Illuminate\Support\Facades\Session::put("sso-jwt", $token);
        \Illuminate\Support\Facades\Session::put("sso-jwt-array", $jwt_explode);
        return redirect(config('ssobridge.sso.application.home_route'));
    });
});
