<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.main.login_route'), 'Newtech\SSOBridge\SSOBridgeController@indexLogin');
    Route::post('/ssoauth/ajax' . config('ssobridge.main.login_route'), 'Newtech\SSOBridge\SSOBridgeController@postLogin');
    Route::post('/ssoauth/ajax/updateProjectConfiguration', 'Newtech\SSOBridge\SSOBridgeController@updateProjectConfiguration');
    Route::get(config('ssobridge.main.logout_route'), 'Newtech\SSOBridge\SSOBridgeController@indexLogout');
    Route::get('/ssoauth/pass_session_dev/{json}', 'Newtech\SSOBridge\SSOBridgeController@passSessionDev');
    Route::get('/ssoauth/pass_session', 'Newtech\SSOBridge\SSOBridgeController@passSession')->name("signed.pass_session")->middleware("signed");
});



Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::post('/ssoauth/requestPassSession', 'Newtech\SSOAuth\SSOAuthController@passSessionPost')->name('api.passSession');
    });
});
