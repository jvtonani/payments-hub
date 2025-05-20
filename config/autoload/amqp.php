<?php
use function Hyperf\Support\env;

return [
    'enable' => true,
    'default' => [
        'host' => env('RABBITMQ_HOST','rabbitmq'),
        'port' => (int) env('RABBITMQ_PORT', 5672),
        'user' => env('RABBITMQ_USER', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
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