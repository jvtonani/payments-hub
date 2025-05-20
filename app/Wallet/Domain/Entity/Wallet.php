<?php

namespace App\Wallet\Domain\Entity;

use App\Shared\Domain\ValueObject\Real;

class Wallet
{
    private string $userId;
    private Real $balance;
    public function __construct(string $userId, Real $amount)
    {
        $this->userId = $userId;
        $this->balance = $amount;
    }

    public static function createWallet(string $userId, float $amount = 0): Wallet
    {
        return new Wallet($userId, new Real($amount));
    }

    public function debit(float $amount): void {
        $this->balance = $this->balance->subtract(new Real($amount));
    }

    public function credit(float $amount): void {
        $this->balance = $this->balance->add(new Real($amount));
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance->getCurrentAmount() >= $amount;
    }

    public function getBalance(): Real
    {
        return $this->balance;
    }

    public function toArray(): array
    {
        return [
            'user_id' => (int) $this->userId,
            'balance' => $this->balance->getCurrentAmount()
        ];
    }
}