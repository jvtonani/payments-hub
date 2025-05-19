<?php

namespace App\Tests\Unit\Wallet\Domain\Entity;

use App\Wallet\Domain\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletUTest extends TestCase
{
    public function testCreateWallet(): void
    {
        $wallet =  Wallet::createWallet('12345678999');

        $this->assertSame(0.0, $wallet->getBalance()->getCurrentAmount());
    }

    public function testCreditAmount(): void
    {
        $wallet =  Wallet::createWallet('12345678999');
        $wallet->credit(10);

        $this->assertSame(10.0, $wallet->getBalance()->getCurrentAmount());
    }

    public function testDebitAmount(): void
    {
        $wallet =  Wallet::createWallet('12345678999', 10);
        $wallet->debit(10);

        $this->assertSame(0.0, $wallet->getBalance()->getCurrentAmount());
    }
}