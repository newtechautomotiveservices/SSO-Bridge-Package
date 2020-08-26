<?php
Route::get('/ssobridge/setToken/{token}', 'Newtech\SSOBridge\SSOController@get_setToken')->middleware('web');
Route::get('/ssobridge/logout', 'Newtech\SSOBridge\SSOController@logout')->middleware('web')->middleware('auth:sso')->name('sso.logout');
Route::get('/ssobridge/changeUser/{id}', 'Newtech\SSOBridge\SSOController@changeUser')->middleware('web')->middleware('auth:sso')->name('sso.change');
Route::get('/ssobridge/changeUser/noauth/{id}', 'Newtech\SSOBridge\SSOController@changeUser')->middleware('web')->name('sso.noauthchange');
Route::get('/ssobridge/refresh', 'Newtech\SSOBridge\SSOController@refreshPermissions')->middleware('web')->name('sso.refresh');
Route::get('/ssobridge/login', 'Newtech\SSOBridge\SSOController@get_setToken')->middleware('web')->middleware('auth:sso')->name('sso.login');
