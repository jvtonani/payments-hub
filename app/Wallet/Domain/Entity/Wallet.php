<?php

namespace App\Wallet\Domain\Entity;

use App\Shared\Domain\ValueObject\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\ValueObject\Real;

class Wallet
{
    private Cpf $cpf;
    private Real $balance;
    public function __construct(Cpf $cpf, Real $amount)
    {
        $this->cpf = $cpf;
        $this->balance = $amount;
    }

    public static function createWallet(string $cpf, float $amount = 0): Wallet
    {
        return new Wallet(new Cpf($cpf), new Real($amount));
    }

    public function debit(float $amount): void {
        $this->balance = $this->balance->subtract(new Real($amount));
    }

    public function credit(float $amount): void {
        $this->balance = $this->balance->add(new Real($amount));
    }

    public function getBalance(): Real
    {
        return $this->balance;
    }
}