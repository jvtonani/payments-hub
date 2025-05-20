<?php

namespace App\Notification\Consumers;

use App\Notification\Model\User;
use App\Notification\Service\NotificationFactory;
use App\Payment\Domain\Entity\Transfer;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Result;

#[Consumer(
    exchange: "transfer_status_exchange",
    queue: "notify_transfer_queue",
    name: "NotifyTransferStatusConsumer",
    nums: 2
)]
class NotifyTransferStatusConsumer extends ConsumerMessage
{
    public function __construct(
        private NotificationFactory $factory
    )
    {
        $this->type = Type::FANOUT;
    }

    public function consume($data): Result
    {
        $notifyService = $this->factory->make($data['transfer_status']);
        $notifyService->notify($data);

        return Result::ACK;
    }
}