<?php

namespace App\Notification\Service;

class NotificationFactory
{
    public function __construct(
        private CreatedNotification $createdNotification,
        private FailedNotification $failedNotification,
        private FinishedNotification $finishedNotification,
    )
    {
    }

    public function make(string $status): NotificationStrategyInterface
    {
        return match ($status) {
            'created' => $this->createdNotification,
            'failed' => $this->failedNotification,
            'finished' => $this->finishedNotification,
            default => throw new \InvalidArgumentException("Status de notificação inválido: {$status}")
        };
    }
}