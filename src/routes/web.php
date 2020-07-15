<?php
Route::get('/ssobridge/setToken/{token}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@get_setToken')->middleware('web');
Route::get('/ssobridge/logout', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@logout')->middleware('web')->middleware('auth:sso')->name('sso.logout');
Route::get('/ssobridge/changeUser/{id}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@changeUser')->middleware('web')->middleware('auth:sso')->name('sso.change');
Route::get('/ssobridge/refresh', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@refreshPermissions')->middleware('web')->name('sso.refresh');
Route::get('/ssobridge/login', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@get_setToken')->middleware('web')->middleware('auth:sso')->name('sso.login');