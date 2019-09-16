<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.main.login_route'), 'Newtech\SSOBridge\SSOBridgeController@indexLogin');
	Route::get(config('ssobridge.main.logout_route'), 'Newtech\SSOBridge\SSOBridgeController@indexLogout');

	Route::get('/ssoauth/pass_session_dev/{json}', 'Newtech\SSOBridge\SSOBridgeController@pass_user_dev');
    Route::get('/ssoauth/pass_session', 'Newtech\SSOBridge\SSOBridgeController@pass_user')->name("signed.pass_session")->middleware("signed");
});



Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::post('/ssoauth/pass_session', 'Newtech\SSOAuth\SSOBridgeController@pass_session')->name('api.passSession');
    });
});
