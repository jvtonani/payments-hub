<?php

namespace App\Tests\Unit\Payment\Domain\ValueObject;

use App\Shared\Domain\ValueObject\TransferStatus;
use PHPUnit\Framework\TestCase;

class TransferStatusUTest extends TestCase
{
    public function testCreateTransferStatusCreated()
    {
        $transferStatus = new TransferStatus(TransferStatus::CREATED);
        $this->assertSame(TransferStatus::CREATED, (string) $transferStatus);
    }
    public function testCreateTransferStatusFinished()
    {
        $transferStatus = new TransferStatus(TransferStatus::FINISHED);
        $this->assertSame(TransferStatus::FINISHED, (string) $transferStatus);
    }
    public function testCreateTransferStatusFailed()
    {
        $transferStatus = new TransferStatus(TransferStatus::FAILED);
        $this->assertSame(TransferStatus::FAILED, (string) $transferStatus);
    }

    public function testCreateTransferStatusInvalid()
    {
        $this->expectException(\DomainException::class);
        new TransferStatus('ANY_INVALID_STATUS');
    }
}