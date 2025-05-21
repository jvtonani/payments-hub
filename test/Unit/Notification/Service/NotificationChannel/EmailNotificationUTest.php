<?php

namespace App\Tests\Unit\Notification\Service\NotificationChannel;

use App\Notification\Service\NotificationChannel\EmailNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class EmailNotificationUTest extends TestCase
{
    private $client;
    private $logger;
    private EmailNotification $emailNotification;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->emailNotification = new EmailNotification($this->client, $this->logger);
    }

    public function testSendCallsClientPostSuccessfully(): void
    {
        $to = 'user@example.com';
        $message = 'mensagem';

        $this->client->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('https://util.devi.tools/api/v1/notify)'),
                $this->equalTo([
                    'json' => [
                        'to' => $to,
                        'message' => $message,
                    ],
                ])
            )
            ->willReturn(new Response(200));

        $this->emailNotification->send($to, $message);
    }

    public function testEmailNotificationException(): void
    {
        $to = 'user@example.com';
        $message = 'Mensagem';

        $this->client->expects($this->exactly(3))
            ->method('post')
            ->willThrowException(new \Exception('Simulando falha no envio'));

        $this->emailNotification->send($to, $message);
    }
}
