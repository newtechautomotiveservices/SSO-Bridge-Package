<?php
Route::group(['middleware' => ['web']], function () {
    Route::get(config('ssobridge.sso.application.login_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogin')->name('sso.auth.login');
	Route::get(config('ssobridge.sso.application.logout_route'), 'Newtech\SSOBridge\App\Http\Controllers\SSOController@indexLogout')->name('sso.auth.logout');
    Route::get('/ssobridge/setToken/{token}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@get_setToken');
});
