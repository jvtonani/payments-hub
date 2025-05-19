<?php

namespace App\Shared\Domain\ValueObject;

class Cnpj implements Document
{
    private string $cnpj;

    public function __construct(string $cnpj)
    {
        $this->setCnpj($cnpj);
    }

    public function __toString(): string
    {
        return $this->cnpj;
    }

    private function setCnpj(string $cnpj): void
    {
        //Check do CNPJ  não foi implementada pela possibilidade de expor CNPJs  reais durante testes
        $sanitizedCnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($sanitizedCnpj) != 14) {
            throw new \DomainException('CNPJ no formato inválido');
        }
        $this->cnpj = $sanitizedCnpj;
    }
}