<?php

namespace App\Shared\Domain\ValueObject\ValueObject;

class Cpf
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        $this->setCpf($cpf);
    }

    public function __toString(): string
    {
        return $this->cpf;
    }

    private function setCpf(string $cpf): void
    {
        //Check do CPF não foi implementada pela possibilidade de expor CPFs reais durante testes
        $sanitizedCpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($sanitizedCpf) != 11) {
            throw new \DomainException('CPF no formato inválido');
        }
        $this->cpf = $sanitizedCpf;
    }
}