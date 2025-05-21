<?php

namespace App\Tests\Unit\Notification\Model;

use App\Notification\Model\User;
use PHPUnit\Framework\TestCase;

class ModelUserUTest extends TestCase
{
    public function testFindByIdReturnsUserArray(): void
    {
        $userId = '123';
        $expectedUser = [
            'id' => $userId,
            'email' => 'user@example.com',
            'cellphone' => '1234567890',
        ];

        $userModel = $this->getMockBuilder(User::class)
            ->onlyMethods(['query'])
            ->getMock();

        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([$expectedUser]);

        $result = $userModel->findById($userId);

        $this->assertSame($expectedUser, $result);
    }

    public function testFindByIdReturnsNullWhenUserNotFound(): void
    {
        $userId = '321';

        $userModel = $this->getMockBuilder(User::class)
            ->onlyMethods(['query'])
            ->getMock();

        $userModel->expects($this->once())
            ->method('query')
            ->willReturn([]);

        $result = $userModel->findById($userId);

        $this->assertNull($result);
    }
}
