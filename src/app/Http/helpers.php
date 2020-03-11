<?php

if (! function_exists('sso_user')) {

    function user()
    {
        return \Auth::user();
    }
}
