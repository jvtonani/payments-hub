<?php

namespace App\Payment\Events;

use Hyperf\Amqp\Message\ProducerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Annotation\Producer;

#[Producer]
class TransferFailedEvent extends ProducerMessage
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
            'status' => $this->transferStatus,
            'transfer_id' => $this->transferId,
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'amount' => $this->amount,
            'transfer_status' => $this->transferStatus,
        ];
    }
}