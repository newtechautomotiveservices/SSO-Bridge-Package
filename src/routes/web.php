<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.sso.login_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogin')->name('sso.auth.login');
	Route::get(config('ssobridge.sso.logout_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogout')->name('sso.auth.logout');
	
});



Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
    	Route::post('/ssoauth/pass_session_dev/{json}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@pass_session_dev')->name('api.passSession.dev');
    	Route::post('/ssoauth/pass_session', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@pass_session')->name('api.passSession');
    });
});
