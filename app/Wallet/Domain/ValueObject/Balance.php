<?php

namespace App\Wallet\Domain\ValueObject;

class Balance
{
    //TODO Utilizar tipo de dado mais adequado para representação de quantia de dinheiro
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function subtract(Balance $amount): self
    {
        $tmpAmount = $this->amount - $amount->getCurrentAmount();
        if($tmpAmount < 0){
            throw new \DomainException("Saldo insuficiente");
        }
        $this->amount = $tmpAmount;

        return $this;
    }

    public function add(Balance $amount): self
    {
        $this->amount = $this->amount + $amount->getCurrentAmount();

        return $this;
    }

    public function getCurrentAmount(): float
    {
        return $this->amount;
    }
}