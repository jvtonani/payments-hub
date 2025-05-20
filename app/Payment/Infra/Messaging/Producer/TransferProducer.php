<?php

namespace App\Payment\Infra\Messaging\Producer;

use App\Payment\Events\TransferFailedEvent;
use Hyperf\Amqp\Producer;
use App\Payment\Events\TransferCreatedEvent;
class TransferProducer
{
    public function __construct(private Producer $producer) {}

    public function publishTransferEvent(
        string $transferId,
        string $payerId,
        string $payeeId,
        int $amount,
        string $transferStatus
    ): void {
        $event = new TransferCreatedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);
        $this->producer->produce($event);
    }

    public function publishTransferFailedEvent(
        string $transferId,
        string $payerId,
        string $payeeId,
        int $amount,
        string $transferStatus
    ): void {
        $event = new TransferFailedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);
        $this->producer->produce($event);
    }
}