<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssoauth.main.login_route'), 'Newtech\SSOAuth\SSOAuthController@indexLogin');
    Route::post('/ssoauth/ajax' . config('ssoauth.main.login_route'), 'Newtech\SSOAuth\SSOAuthController@postLogin');
    Route::post('/ssoauth/ajax/updateProjectConfiguration', 'Newtech\SSOAuth\SSOAuthController@updateProjectConfiguration');
    Route::get(config('ssoauth.main.logout_route'), 'Newtech\SSOAuth\SSOAuthController@indexLogout');
    Route::get('/ssoauth/pass_session_dev/{json}', 'Newtech\SSOAuth\SSOAuthController@passSessionDev');
    Route::get('/ssoauth/pass_session', 'Newtech\SSOAuth\SSOAuthController@passSession')->name("signed.pass_session")->middleware("signed");
});



Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::post('/ssoauth/requestPassSession', 'Newtech\SSOAuth\SSOAuthController@passSessionPost')->name('api.passSession');
    });
});
