<?php

namespace App\Shared\Domain\ValueObject;

class Real
{
    //TODO Utilizar tipo de dado mais adequado para representação de quantia de dinheiro
    private float $amount;

    public function __construct(float $amount)
    {
        $this->amount = round($amount, 5);
    }

    public function subtract(Real $amount): self
    {
        $tmpAmount = $this->amount - $amount->getCurrentAmount();
        if($tmpAmount < 0){
            throw new \DomainException("Saldo insuficiente");
        }
        $this->amount = $tmpAmount;

        return $this;
    }

    public function add(Real $amount): self
    {
        $this->amount = $this->amount + $amount->getCurrentAmount();

        return $this;
    }

    public function getCurrentAmount(): float
    {
        return $this->amount;
    }
}