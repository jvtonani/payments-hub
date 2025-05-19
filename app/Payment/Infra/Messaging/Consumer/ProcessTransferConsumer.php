<?php

namespace App\Payment\Infra\Messaging\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;

#[Consumer(
    exchange: "transfer_exchange",
    routingKey: "transfer.created",
    queue: "transfer_processing_queue",
    name: "ProcessTransferConsumer",
)]
class ProcessTransferConsumer extends ConsumerMessage
{
    public function consume($data): Result
    {
        var_dump("TA AQUI RAPAZ");
        return Result::ACK;
    }
}