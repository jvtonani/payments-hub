<?php

namespace App\Tests\Unit\User\Domain\Entity;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\ValueObject\UserType;
use PHPUnit\Framework\TestCase;

class UserUTest extends TestCase
{
    public function testCreateUserValidTransfer(): void
    {
        $user = User::createUser(
            '12345678946',
            'Username',
            'email@email.com',
            UserType::COMMOM,
            'password',
            1
        );

        $document = $user->getUserDocument();

        $this->assertSame('email@email.com', (string) $user->getEmail());
        $this->assertSame('12345678946', (string) $document);
        $this->assertTrue($user->getUserType()->canTransfer());

        $user->setId(3);
        $userArray = $user->toArray();

        $this->assertSame(3, $userArray['id']);

    }

    public function testCreateUserInvalidTransfer(): void
    {
        $user = User::createUser(
            '12345678946',
            'Username',
            'email@email.com',
            UserType::MERCHANT,
            'password',
            2
        );

        $this->assertFalse($user->getUserType()->canTransfer());
    }
}
