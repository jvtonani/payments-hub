<?php

namespace App\Tests\Unit\Notification\Service;

use App\Notification\Model\User;
use App\Notification\Service\CreatedNotification;
use App\Notification\Service\NotificationChannel\EmailNotification;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CreatedNotificationUTest extends TestCase
{
    public function testNotifySendsEmailAndLogsInfo(): void
    {
        $data = [
            'payer_id' => '123',
        ];

        $userData = [
            'id' => '123',
            'email' => 'payer@example.com',
        ];

        $userModel = $this->createMock(User::class);
        $emailNotification = $this->createMock(EmailNotification::class);
        $logger = $this->createMock(LoggerInterface::class);

        $userModel->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn($userData);

        $emailNotification->expects($this->once())
            ->method('send')
            ->with('payer@example.com', 'Sua transferência foi criada. Você será notificado quando for finalizada');

        $createdNotification = new CreatedNotification($userModel, $emailNotification, $logger);

        $createdNotification->notify($data);
    }
}
