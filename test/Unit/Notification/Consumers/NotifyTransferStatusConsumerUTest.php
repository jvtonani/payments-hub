<?php

namespace App\Tests\Unit\Notification\Consumers;

use App\Notification\Consumers\NotifyTransferStatusConsumer;
use App\Notification\Service\NotificationFactory;
use App\Notification\Service\NotificationStrategyInterface;
use Hyperf\Amqp\Result;
use PHPUnit\Framework\TestCase;

class NotifyTransferStatusConsumerUTest extends TestCase
{
    public function testConsumeShouldNotifyAndReturnAck()
    {
        $data = [
            'transfer_id' => '123',
            'payer_id' => '1',
            'payee_id' => '2',
            'amount' => 500,
            'transfer_status' => 'finished',
        ];

        $notificationStrategy = $this->createMock(NotificationStrategyInterface::class);
        $notificationStrategy->expects($this->once())
            ->method('notify')
            ->with($data);

        $notificationFactory = $this->createMock(NotificationFactory::class);
        $notificationFactory->expects($this->once())
            ->method('make')
            ->with('finished')
            ->willReturn($notificationStrategy);

        $consumer = new NotifyTransferStatusConsumer($notificationFactory);

        $result = $consumer->consume($data);

        $this->assertSame(Result::ACK, $result);
    }
}
