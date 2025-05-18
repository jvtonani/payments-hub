<?php

namespace App\Wallet\Domain\Entity;

use App\User\Domain\ValueObject\Cpf;
use App\Wallet\Domain\ValueObject\Balance;

class Wallet
{
    private Cpf $cpf;
    private Balance $balance;

    public function debit(Balance $balance): void {
        $this->balance = $this->balance->subtract($balance);
    }

    public function credit(Balance $balance): void {
        $this->balance = $this->balance->add($balance);
    }
}