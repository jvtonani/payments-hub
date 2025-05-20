<?php

namespace App\Notification\Service\NotificationChannel;

use App\Shared\Helper\RetryHelper;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class SmsNotification implements NotificationChannelInterface
{
    private const SMS_NOTIFICATION_URL = 'https://util.devi.tools/api/v1/notify)';
    private const MAX_RETRIES = 3;

    public function __construct(
        private Client          $client,
        private LoggerInterface $logger
    )
    {
    }

    public function send(string $to, string $message): void
    {
        try {
            RetryHelper::run(self::MAX_RETRIES, function () use ($to, $message) {
                $this->client->post(self::SMS_NOTIFICATION_URL, [
                    'json' => [
                        'to' => $to,
                        'message' => $message,
                    ],
                ]);
            });
        } catch (\Throwable $e) {
            $this->logger->info("Falha ao notificar por SMS: " . $e->getMessage());
        }
    }
}