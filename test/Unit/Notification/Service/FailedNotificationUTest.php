<?php

namespace App\Tests\Unit\Notification\Service;

use App\Notification\Model\User;
use App\Notification\Service\FailedNotification;
use App\Notification\Service\NotificationChannel\SmsNotification;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FailedNotificationUTest extends TestCase
{
    public function testNotifySendsSmsAndLogsInfo(): void
    {
        $data = [
            'payer_id' => '456',
        ];

        $userData = [
            'id' => '456',
            'cellphone' => '5511999999999',
        ];

        $userModel = $this->createMock(User::class);
        $smsNotification = $this->createMock(SmsNotification::class);
        $logger = $this->createMock(LoggerInterface::class);

        $userModel->expects($this->once())
            ->method('findById')
            ->with('456')
            ->willReturn($userData);

        $smsNotification->expects($this->once())
            ->method('send')
            ->with('5511999999999', 'Sua transferência não foi finalizada. Tente novamente.');

        $failedNotification = new FailedNotification($userModel, $smsNotification, $logger);

        $failedNotification->notify($data);
    }
}
