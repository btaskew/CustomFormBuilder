<?php

return [
    'domain' => 'https://ssocore.exeter.ac.uk',

    'uri' => 'openam',

    'realm' => 'people',

    'cookieName' => 'iPlanetDirectoryPro',

    'secureCookie' => true,

    // If implicit login is allowed, any sso user may log in automatically registering an app user
    // If it is not allowed the user must be registered in the app by an admin before the can log in
    'allowImplicitLogin' => false,
];
