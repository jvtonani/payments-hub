<?php

namespace App\Notification\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Result;

#[Consumer(
    exchange: "transfer_status_exchange",
    queue: "process_transfer_queue",
    name: "ProcessTransferConsumer",
    nums: 2
)]
class NotifyTransferStatusConsumer extends ConsumerMessage
{
    public function __construct()
    {
        $this->type = Type::FANOUT;
    }

    public function consume($data): Result
    {
        return Result::ACK;
    }
}