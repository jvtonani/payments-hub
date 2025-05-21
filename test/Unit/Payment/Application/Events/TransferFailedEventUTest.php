<?php

namespace App\Tests\Unit\Payment\Application\Events;

use App\Payment\Application\Events\TransferFailedEvent;
use PHPUnit\Framework\TestCase;

class TransferFailedEventUTest extends TestCase
{
    public function testConstructorSetsAllProperties(): void
    {
        $transferId = '123';
        $payerId = '1';
        $payeeId = '2';
        $amount = 1000;
        $transferStatus = 'failed';

        $event = new TransferFailedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);

       $this->assertIsObject($event);
    }
}
