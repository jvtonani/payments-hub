<?php

namespace App\Notification\Service\NotificationChannel;

use App\Onboarding\Domain\Entity\User;
use App\Shared\Domain\ValueObject\TransferStatus;

interface NotificationChannelInterface
{
    public function send(string $to, string $message): void;
}