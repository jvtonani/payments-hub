<?php

namespace App\Onboarding\Domain\ValueObject;

class UserType
{
    public const MERCHANT = 'merchant';
    public const COMMOM = 'common';
    private array $typeEnum = [self::MERCHANT, self::COMMOM];
    private string $type;
    private bool $canTransfer;
    public function __construct(string $type)
    {
        $this->setType($type);
        $this->setCanTransfer($type);
    }

    public function __toString(): string
    {
        return $this->type;
    }

    public function canTransfer(): bool
    {
        return $this->canTransfer;
    }

    private function setType(string $type): void
    {
        if (!in_array($type, $this->typeEnum)) {
            throw new \DomainException('Tipo de usuário inválido');
        }

        $this->type = $type;
    }
    private function setCanTransfer(string $type): void
    {
        $this->canTransfer = match ($type) {
            self::MERCHANT => false,
            self::COMMOM => true
        };
    }
}