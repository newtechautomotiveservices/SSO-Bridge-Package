<?php
Route::get('/ssobridge/setToken/{token}', 'Newtech\SSOBridge\SSOController@get_setToken')->middleware('web');
Route::get('/ssobridge/logout', 'Newtech\SSOBridge\SSOController@logout')->middleware('web')->name('sso.logout');
Route::get('/ssobridge/changeUser/{id}', 'Newtech\SSOBridge\SSOController@changeUser')->middleware('web')->name('sso.change');
Route::get('/ssobridge/refresh', 'Newtech\SSOBridge\SSOController@refreshPermissions')->middleware('web')->name('sso.refresh');

