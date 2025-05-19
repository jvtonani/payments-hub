<?php

namespace App\Payment\Infra\Messaging\Producer;

use Hyperf\Amqp\Producer;
use App\Payment\Events\TransferCreatedEvent;
class TransferProducer
{
    public function __construct(private Producer $producer) {}

    public function publishTransferEvent(
        string $transactionId,
        string $payerId,
        string $payeeId,
        int $amount
    ): void {
        $event = new TransferCreatedEvent($transactionId, $payerId, $payeeId, $amount);
        $this->producer->produce($event);
    }
}