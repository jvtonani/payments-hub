<?php

namespace App\Tests\Unit\Payment\Infra\Messaging\Producer;

use App\Payment\Application\Events\TransferCreatedEvent;
use App\Payment\Application\Events\TransferFailedEvent;
use App\Payment\Application\Events\TransferFinishedEvent;
use App\Payment\Infra\Messaging\Producer\TransferProducer;
use Hyperf\Amqp\Producer;
use PHPUnit\Framework\TestCase;

class TransferProducerUTest extends TestCase
{
    private Producer $producerMock;
    private TransferProducer $transferProducer;

    protected function setUp(): void
    {
        $this->producerMock = $this->createMock(Producer::class);
        $this->transferProducer = new TransferProducer($this->producerMock);
    }

    public function testPublishTransferEvent(): void
    {
        $this->producerMock
            ->expects($this->once())
            ->method('produce')
            ->with($this->isInstanceOf(TransferCreatedEvent::class));

        $this->transferProducer->publishTransferEvent(
            '123', '1', '2', 1000, 'created'
        );
    }

    public function testPublishTransferFailedEvent(): void
    {
        $this->producerMock
            ->expects($this->once())
            ->method('produce')
            ->with($this->isInstanceOf(TransferFailedEvent::class));

        $this->transferProducer->publishTransferFailedEvent(
            '123', '1', '2', 1000, 'failed'
        );
    }

    public function testPublishTransferFinishedEvent(): void
    {
        $this->producerMock
            ->expects($this->once())
            ->method('produce')
            ->with($this->isInstanceOf(TransferFinishedEvent::class));

        $this->transferProducer->publishTransferFinishedEvent(
            '123', '1', '2', 1000, 'finished'
        );
    }
}
