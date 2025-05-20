<?php

namespace App\Tests\Unit\Payment\Domain\Entity;

use App\Payment\Domain\Entity\Transfer;
use App\Shared\Domain\ValueObject\TransferStatus;
use PHPUnit\Framework\TestCase;

class TransferUTest extends TestCase
{
    public function testCreateTransfer(): void
    {
        $transfer = Transfer::createTransfer('12345678999','15988999844', 0);
        $transfer->setTransferId(5);
        $transferArray = $transfer->toArray();

        $this->assertSame(5, $transferArray['id']);
        $this->assertSame(TransferStatus::CREATED, $transferArray['transfer_status']);

        $this->assertSame((string) $transfer->getTransferStatus(), TransferStatus::CREATED);
    }

    public function testFinishedTransfer(): void
    {
        $transfer = Transfer::createTransfer('12345678999','15988999844', 0);
        $transfer->setStatusFinished();

        $this->assertSame((string) $transfer->getTransferStatus(), TransferStatus::FINISHED);
    }

    public function testFailedTransfer(): void
    {
        $transfer = Transfer::createTransfer('12345678999','15988999844', 0);
        $transfer->setStatusFailed();

        $this->assertSame((string) $transfer->getTransferStatus(), TransferStatus::FAILED);
    }
}