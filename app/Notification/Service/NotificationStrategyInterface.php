<?php

namespace App\Notification\Service;

interface NotificationStrategyInterface
{
    public function notify(array $data): void;
}