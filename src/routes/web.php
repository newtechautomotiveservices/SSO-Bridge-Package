<?php
Route::get('/ssobridge/setToken/{token}', 'Newtech\SSOBridge\App\Http\Controllers\SSOController@get_setToken')->middleware('web');