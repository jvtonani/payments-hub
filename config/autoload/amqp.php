<?php

return [
    'default' => [
    'host' => 'rabbitmq',
    'port' => 5672,
    'user' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
    ],
    'producers' => [
        \App\Payment\Events\TransferCreatedEvent::class
    ],
    'consumers' => [
        \App\Payment\Infra\Messaging\Consumer\ProcessTransferConsumer::class
    ],
    'heartbeat' => 30,
    'connection_timeout' => 10,
    'read_write_timeout' => 30,
];
