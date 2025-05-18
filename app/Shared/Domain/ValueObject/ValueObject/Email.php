<?php

namespace App\Shared\Domain\ValueObject\ValueObject;

class Email
{
    private string $address;

    public function __construct(string $address)
    {
        $this->validateAndSetEmail($address);
    }

    public function __toString(): string
    {
        return $this->address;
    }

    private function validateAndSetEmail(string $address)
    {
        if(filter_var($address, FILTER_VALIDATE_EMAIL) === false){
            throw new \DomainException('Email inválido');
        }
        $this->address = $address;
    }
}