<?php

namespace App\Payment\Domain\ValueObject;

class TransferStatus
{
    const CREATED = 'created';
    const FINISHED = 'finished';
    const FAILED = 'failed';
    private array $typeEnum = [self::CREATED, self::FINISHED, self::FAILED];
    private string $type;

    public function __construct(string $type)
    {
        if (!in_array($type, $this->typeEnum)) {
            throw new \DomainException('Transferência com status inválido');
        }
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}