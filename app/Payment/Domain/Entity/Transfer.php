<?php

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\Real;
use App\Shared\Domain\ValueObject\TransferStatus;

class Transfer
{
    private string $payeeUserId;
    private string $payerUserId;
    private Real $amount;
    private \DateTimeImmutable $createdAt;
    private TransferStatus $transferStatus;
    private mixed $transferId;

    public function __construct(string $payeeUserId, string $payerUserId, Real $amount, TransferStatus $transferStatus)
    {
        $this->payeeUserId = $payeeUserId;
        $this->payerUserId = $payerUserId;
        $this->amount = $amount;
        $this->transferStatus = $transferStatus;
        $this->createdAt = new \DateTimeImmutable();
        $this->transferId = null;
    }

    public static function createTransfer(string $payeeUserId, string $payerUserId, float $amount, string $transferStatus = TransferStatus::CREATED): self
    {
        $transferStatus = self::buildTransferStatus($transferStatus);
        return new Transfer($payeeUserId, $payerUserId, new Real($amount), $transferStatus);
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

    public function getTransferBalance(): float
    {
        return $this->amount->getCurrentAmount();
    }

    public function setTransferId($transferId): void
    {
        $this->transferId = $transferId;
    }

    public function getPayerUserId(): string
    {
        return $this->payerUserId;
    }

    public function getPayeeUserId(): string
    {
        return $this->payeeUserId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->transferId,
            'payee_user_id' => (string) $this->payeeUserId,
            'payer_user_id' => (string) $this->payerUserId,
            'amount' => (string) $this->amount->getCurrentAmount(),
            'created_at' =>  (string) $this->createdAt->format('Y-m-d H:i:s'),
            'transfer_status' => (string) $this->transferStatus
        ];
    }

    private static function buildTransferStatus(string $transferStatus): TransferStatus
    {
        return match ($transferStatus) {
            TransferStatus::CREATED => new TransferStatus(TransferStatus::CREATED),
            TransferStatus::FAILED => new TransferStatus(TransferStatus::FAILED),
            TransferStatus::FINISHED => new TransferStatus(TransferStatus::FINISHED),
            default => new \DomainException('Status de transferência inválido')
        };
    }
}