<?php

namespace App\Tests\Unit\Wallet\Domain\ValueObject;

use App\Wallet\Domain\ValueObject\Balance;
use PHPUnit\Framework\TestCase;

class BalanceUTest extends TestCase
{
    public function testCreateAmount(): void
    {
        $amount = 10.20033;
        $balance = new Balance($amount);

        $this->assertSame($amount, $balance->getCurrentAmount());
    }

    public function testSubtractAmountInsuficientBalance(): void
    {
        $this->expectException(\DomainException::class);

        $amount = 10;
        $balance = new Balance($amount);

        $balance->subtract(new Balance(100));
    }

    public function testSetZeroAmount(): void
    {
        $amount = 10;
        $balance = new Balance($amount);

        $this->assertSame(0.0, $balance->subtract(new Balance($amount))->getCurrentAmount());
    }

    public function testAddAmount(): void
    {
        $amount = 10;
        $balance = new Balance($amount);

        $this->assertSame(20.0, $balance->add(new Balance($amount))->getCurrentAmount());
    }
}