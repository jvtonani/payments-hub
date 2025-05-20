<?php

namespace App\Payment\Infra\Messaging\Producer;

use App\Payment\Application\Events\TransferCreatedEvent;
use App\Payment\Application\Events\TransferFailedEvent;
use App\Payment\Application\Events\TransferFinishedEvent;
use Hyperf\Amqp\Producer;

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

    public function publishTransferFinishedEvent(
        string $transferId,
        string $payerId,
        string $payeeId,
        int $amount,
        string $transferStatus
    ): void {
        $event = new TransferFinishedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);
        $this->producer->produce($event);
    }
}