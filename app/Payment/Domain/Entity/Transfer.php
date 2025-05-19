<?php

namespace App\Payment\Domain\Entity;

use App\Payment\Domain\ValueObject\TransferStatus;
use App\Shared\Domain\Builder\DocumentBuilder;
use App\Shared\Domain\ValueObject\Document;
use App\Shared\Domain\ValueObject\Real;

class Transfer
{
    private Document $payeeDocument;
    private Document $payerDocument;
    private Real $amount;
    private \DateTimeImmutable $createdAt;
    private TransferStatus $transferStatus;
    private mixed $transferId;

    public function __construct(Document $payeeDocument, Document $payerDocument, Real $amount)
    {
        $this->payeeDocument = $payeeDocument;
        $this->payerDocument = $payerDocument;
        $this->amount = $amount;
        $this->transferStatus = new TransferStatus(TransferStatus::CREATED);
        $this->createdAt = new \DateTimeImmutable();
        $this->transferId = null;
    }

    public static function  createTransfer(string $payeeDocument, string $payerDocument, float $amount): self
    {
        $payeeDocument = new DocumentBuilder($payeeDocument);
        $payerDocument = new DocumentBuilder($payerDocument);
        return new Transfer($payeeDocument->getDocument(), $payerDocument->getDocument(), new Real($amount));
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

    public function setTransferId($transferId): void
    {
        $this->transferId = $transferId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->transferId,
            'payee_document' => (string) $this->payeeDocument,
            'payer_document' => (string) $this->payerDocument,
            'status' => (string) $this->transferStatus,
            'amount' => (string) $this->amount->getCurrentAmount(),
            'created_at' =>  (string) $this->createdAt->format('Y-m-d H:i:s'),
            'transfer_status' => (string) $this->transferStatus
        ];
    }
}