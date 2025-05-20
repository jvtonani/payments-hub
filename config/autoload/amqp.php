<?php

return [
    'enable' => true,
    'default' => [
        'host' => 'rabbitmq',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'concurrent' => [
            'limit' => 10,
        ],
        'pool' => [
            'connections' => 10,
        ],
        'params' => [
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 60.0,
            'read_write_timeout' => 60.0,
            'context' => null,
            'keepalive' => false,
            'heartbeat' => 0,
            'close_on_destruct' => false,
        ],
    ]
];