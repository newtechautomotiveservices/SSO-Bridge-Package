<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.sso.login_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogin');
	Route::get(config('ssobridge.sso.logout_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogout');

	Route::get('/ssoauth/pass_session_dev/{json}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@pass_session_dev');
    Route::get('/ssoauth/pass_session', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@pass_session')->name("signed.pass_session")->middleware("signed");
});



Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::post('/ssoauth/pass_session', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@request_pass_session')->name('api.passSession');
    });
});
