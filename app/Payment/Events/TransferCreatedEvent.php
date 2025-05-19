<?php

namespace App\Payment\Events;

use Hyperf\Amqp\Message\ProducerMessage;
use Hyperf\Amqp\Message\Type;

class TransferCreatedEvent extends ProducerMessage
{
    public function __construct(
        public readonly string $transactionId,
        public readonly string $payerId,
        public readonly string $payeeId,
        public readonly int $amount
    ) {
        $this->type = Type::DIRECT;
        $this->exchange = 'transfer_exchange';
        $this->routingKey = 'transfer.created';
    }

    public function getPayload(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'amount' => $this->amount
        ];
    }
}