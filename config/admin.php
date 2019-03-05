<?php

return [
    'apps' => [
        'admin' => [
            'name' => 'Admin',
            'models' => [
                'users' => [
                    'name' => 'Users',
                    'route' => 'admin.users.index',
                    'guard' => 'administer',
                    'guardKey' => App\User::class,
                ],
                'folders' => [
                    'name' => 'Folders',
                    'route' => 'folders.index',
                    'guard' => 'administer',
                    'guardKey' => App\Folder::class,
                ],
                'questionBank' => [
                    'name' => 'Question bank',
                    'route' => 'question-bank.create',
                    'guard' => 'administer',
                    'guardKey' => App\Question::class,
                ]
            ],
        ],
    ],
    'route' => [

        /*
         * URL Prefix
         * The URL prefix for all URL
         */
        'urlPrefix' => 'admin',

        /*
         * Route Prefix
         * The URL prefix for all routes
         */
        'routePrefix' => 'admin.',

        /*
         * Route Middleware
         * Pass an array of Middleware to apply to all routes
         */
        'middleware' => ['web', 'auth.admin'],

    ],
];