<?php

namespace App\Tests\Unit\Notification\Service\NotificationChannel;

use App\Notification\Service\NotificationChannel\SmsNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SmsNotificationUTest extends TestCase
{
    private $client;
    private $logger;
    private SmsNotification $smsNotification;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->smsNotification = new SmsNotification($this->client, $this->logger);
    }

    public function testSendCallsClientPostSuccessfully(): void
    {
        $to = '1234567890';
        $message = 'message';

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

        $this->smsNotification->send($to, $message);
    }

    public function testSendLogsErrorOnException(): void
    {
        $to = '1234567890';
        $message = 'message';

        $this->client->expects($this->exactly(3))
            ->method('post')
            ->willThrowException(new \Exception(''));

        $this->smsNotification->send($to, $message);
    }

}
