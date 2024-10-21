<?php
return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'espaci45_intranet'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'bluehosting' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_BLUE', 'localhost'),
            'port' => env('DB_PORT_BLUE', '3306'),
            'database' => env('DB_DATABASE_BLUE', 'espaci45_intranet'),
            'username' => env('DB_USERNAME_BLUE', 'espaci45_joseph'),
            'password' => env('DB_PASSWORD_BLUE', 'Inacap.23'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],
];
