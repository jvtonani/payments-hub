<?php

namespace App\Notification\Service\NotificationChannel;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use App\Shared\Helper\RetryHelper;

class EmailNotification implements NotificationChannelInterface
{
    private const EMAIL_NOTIFICATION_URL = 'https://util.devi.tools/api/v1/notify)';
    private const MAX_RETRIES = 3;

    public function __construct(
        private Client          $client,
        private LoggerInterface $logger
    )
    {
    }

    //Aqui estaria a lógica de envio para o serviço Email
    public function send(string $to, string $message): void
    {
        try {
            RetryHelper::run(self::MAX_RETRIES, function () use ($to, $message) {
                $this->client->post(self::EMAIL_NOTIFICATION_URL, [
                    'json' => [
                        'to' => $to,
                        'message' => $message,
                    ],
                ]);
            });
        } catch (\Throwable $e) {
            $this->logger->info("Falha ao notificar por EMAIL: " . $e->getMessage());
        }
    }
}