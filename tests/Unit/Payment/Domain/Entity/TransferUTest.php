<?php

namespace App\Tests\Unit\Payment\Domain\Entity;

use App\Payment\Domain\ValueObject\TransferStatus;
use PHPUnit\Framework\TestCase;
use App\Payment\Domain\Entity\Transfer;

class TransferUTest extends TestCase
{
    public function testCreateTransfer(): void
    {
        $transfer = Transfer::createTransfer('12345678999','15988999844', 0);

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