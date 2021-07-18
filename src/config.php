<?php

use App\Database\Connection\Connection;

return [
    'database' => [
        'driver' => Connection::MYSQL_CONNECTION,
        'connections' => [
            Connection::MYSQL_CONNECTION => [
                'host' => '192.168.10.10',
                'dbname' => 'blog',
                'user' => 'homestead',
                'password' => 'secret',
            ],
            Connection::SQLITE_CONNECTION => [
                'dbname' => 'blog',
            ],
        ],
    ],
    'migration' => [
        'default_user' => [
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => 'password',
        ]
    ]
];
