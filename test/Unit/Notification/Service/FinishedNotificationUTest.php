<?php

namespace App\Tests\Unit\Notification\Service;

use App\Notification\Model\User;
use App\Notification\Service\FinishedNotification;
use App\Notification\Service\NotificationChannel\EmailNotification;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FinishedNotificationUTest extends TestCase
{
    public function testNotifySendsEmailsAndLogsInfo(): void
    {
        $data = [
            'payer_id' => '123',
            'payee_id' => '321',
        ];

        $payerData = [
            'id' => '123',
            'email' => 'payer@example.com',
        ];

        $payeeData = [
            'id' => '321',
            'email' => 'payee@example.com',
        ];

        $userModel = $this->createMock(User::class);
        $emailNotification = $this->createMock(EmailNotification::class);
        $logger = $this->createMock(LoggerInterface::class);


        $userModel->expects($this->exactly(2))
            ->method('findById')
            ->willReturnOnConsecutiveCalls($payerData, $payeeData);

        $finishedNotification = new FinishedNotification($userModel, $emailNotification, $logger);

        $finishedNotification->notify($data);
    }
}
