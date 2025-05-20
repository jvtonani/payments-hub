<?php

namespace App\Payment\Application\Events;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;
use Hyperf\Amqp\Message\Type;

#[Producer]
class TransferCreatedEvent extends ProducerMessage
{
    public function __construct(
        public readonly string $transferId,
        public readonly string $payerId,
        public readonly string $payeeId,
        public readonly int $amount,
        public readonly string $transferStatus
    ) {
        $this->type = Type::FANOUT;
        $this->exchange = 'transfer_status_exchange';
        $this->payload = [
            'transfer_id' => $this->transferId,
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'amount' => $this->amount,
            'transfer_status' => $this->transferStatus,
        ];
    }
}