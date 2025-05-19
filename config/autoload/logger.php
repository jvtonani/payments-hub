<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

return [
    'default' => [
        'handlers' => [
            [
                'class' => StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level' => \Monolog\Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => LineFormatter::class,
                    'constructor' => [
                        'format' => "[%datetime%] %channel%.%level_name%: %message% %context%\n",
                        'dateFormat' => "Y-m-d H:i:s",
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
];
