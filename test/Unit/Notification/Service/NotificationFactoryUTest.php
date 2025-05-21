<?php

namespace App\Tests\Unit\Notification\Service;

use App\Notification\Service\CreatedNotification;
use App\Notification\Service\FailedNotification;
use App\Notification\Service\FinishedNotification;
use App\Notification\Service\NotificationFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NotificationFactoryUTest extends TestCase
{
    private CreatedNotification $createdNotification;
    private FailedNotification $failedNotification;
    private FinishedNotification $finishedNotification;
    private NotificationFactory $factory;

    protected function setUp(): void
    {
        $this->createdNotification = $this->createMock(CreatedNotification::class);
        $this->failedNotification = $this->createMock(FailedNotification::class);
        $this->finishedNotification = $this->createMock(FinishedNotification::class);

        $this->factory = new NotificationFactory(
            $this->createdNotification,
            $this->failedNotification,
            $this->finishedNotification
        );
    }

    public function testMakeReturnsCreatedNotification(): void
    {
        $notification = $this->factory->make('created');
        $this->assertSame($this->createdNotification, $notification);
    }

    public function testMakeReturnsFailedNotification(): void
    {
        $notification = $this->factory->make('failed');
        $this->assertSame($this->failedNotification, $notification);
    }

    public function testMakeReturnsFinishedNotification(): void
    {
        $notification = $this->factory->make('finished');
        $this->assertSame($this->finishedNotification, $notification);
    }

    public function testMakeThrowsExceptionForInvalidStatus(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Status de notificação inválido: unknown');

        $this->factory->make('unknown');
    }
}
