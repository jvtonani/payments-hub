<?php

namespace App\Tests\Unit\Shared\ValueObject;

use App\Shared\Domain\ValueObject\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailUTest extends TestCase
{
    public function testValidEmail(): void
    {
        $address = 'valid_email@domain.com';
        $email = new Email($address);;

        $this->assertEquals($address, (string) $email);
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(\DomainException::class);
        $address = 'invalid_email';

        new Email($address);
    }
}