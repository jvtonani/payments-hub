<?php

namespace App\Tests\Unit\User\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Onboarding\Domain\ValueObject\UserType;

class UserTypeUTest extends TestCase
{
    public function testCreateUserTypeMerchant(): void
    {
        $userType = new UserType(UserType::MERCHANT);
        $this->assertEquals(UserType::MERCHANT, $userType);
        $this->assertFalse($userType->canTransfer());
    }

    public function testCreateUserTypeCommon(): void
    {
        $userType = new UserType(UserType::COMMOM);
        $this->assertEquals(UserType::COMMOM, $userType);
        $this->assertTrue($userType->canTransfer());
    }

    public function testCreateUserTypeInvalid(): void
    {
        $this->expectException(\DomainException::class);
        new UserType('INVALID');
    }
}