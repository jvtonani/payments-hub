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
            'password'
        );

        $this->assertTrue($user->getUserType()->canTransfer());
    }

    public function testCreateUserInvalidTransfer(): void
    {
        $user = User::createUser(
            '12345678946',
            'Username',
            'email@email.com',
            UserType::MERCHANT,
            'password'
        );

        $this->assertFalse($user->getUserType()->canTransfer());
    }
}
