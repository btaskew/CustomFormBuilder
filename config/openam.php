<?php

return [
    'domain' => env('OPENAM_DOMAIN'),

    'uri' => env('OPENAM_URI'),

    'realm' => env('OPENAM_REALM'),

    'cookieName' => env('OPENAM_COOKIE_NAME'),

    'cookieDomain' => env('OPENAM_COOKIE_DOMAIN'),

    'cookiePath' => env('OPENAM_COOKIE_PATH'),

    'secureCookie' => true,

    // If implicit login is allowed, any sso user may log in automatically registering an app user
    // If it is not allowed the user must be registered in the app by an admin before the can log in
    'allowImplicitLogin' => false,
];
