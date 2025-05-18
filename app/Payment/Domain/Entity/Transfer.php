<?php

namespace App\Payment\Domain\Entity;

use App\Payment\Domain\ValueObject\TransferStatus;
use App\Shared\Domain\ValueObject\ValueObject\Cpf;
use App\Shared\Domain\ValueObject\ValueObject\Real;

class Transfer
{
    private Cpf $payeeCpf;
    private Cpf $payerCpf;
    private Real $amount;
    private \DateTimeImmutable $createdAt;
    private TransferStatus $transferStatus;

    public function __construct(Cpf $payeeCpf, Cpf $payerCpf, Real $amount)
    {
        $this->payeeCpf = $payeeCpf;
        $this->payerCpf = $payerCpf;
        $this->amount = $amount;
        $this->transferStatus = new TransferStatus(TransferStatus::CREATED);
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function createTransfer(string $payeeCpf, string $payerCpf, float $amount): self
    {
        return new Transfer(new Cpf($payeeCpf), new Cpf($payerCpf), new Real($amount));
    }

    public function setStatusFinished(): void
    {
        $this->transferStatus = new TransferStatus(TransferStatus::FINISHED);
    }

    public function setStatusFailed(): void
    {
        $this->transferStatus = new TransferStatus(TransferStatus::FAILED);
    }

    public function getTransferStatus(): TransferStatus
    {
        return $this->transferStatus;
    }
}