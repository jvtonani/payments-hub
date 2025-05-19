<?php

namespace App\Tests\Unit\Shared\ValueObject;

use App\Shared\Domain\ValueObject\Real;
use PHPUnit\Framework\TestCase;

class RealUTest extends TestCase
{
    public function testCreateAmount(): void
    {
        $amount = 10.20033;
        $balance = new Real($amount);

        $this->assertSame($amount, $balance->getCurrentAmount());
    }

    public function testSubtractAmountInsuficientBalance(): void
    {
        $this->expectException(\DomainException::class);

        $amount = 10;
        $balance = new Real($amount);

        $balance->subtract(new Real(100));
    }

    public function testSetZeroAmount(): void
    {
        $amount = 10;
        $balance = new Real($amount);

        $this->assertSame(0.0, $balance->subtract(new Real($amount))->getCurrentAmount());
    }

    public function testAddAmount(): void
    {
        $amount = 10;
        $balance = new Real($amount);

        $this->assertSame(20.0, $balance->add(new Real($amount))->getCurrentAmount());
    }
}