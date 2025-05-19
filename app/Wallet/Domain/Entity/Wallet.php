<?php

namespace App\Wallet\Domain\Entity;

use App\Shared\Domain\Builder\DocumentBuilder;
use App\Shared\Domain\ValueObject\Document;
use App\Shared\Domain\ValueObject\Real;

class Wallet
{
    private Document $document;
    private Real $balance;
    public function __construct(Document $document, Real $amount)
    {
        $this->document = $document;
        $this->balance = $amount;
    }

    public static function createWallet(string $documentNumber, float $amount = 0): Wallet
    {
        $document = new DocumentBuilder($documentNumber);
        return new Wallet($document->getDocument(), new Real($amount));
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