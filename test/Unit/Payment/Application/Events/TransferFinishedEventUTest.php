<?php

namespace App\Tests\Unit\Payment\Application\Events;

use App\Payment\Application\Events\TransferFinishedEvent;
use PHPUnit\Framework\TestCase;

class TransferFinishedEventUTest extends TestCase
{
    public function testConstructorSetsAllProperties(): void
    {
        $transferId = '123';
        $payerId = '1';
        $payeeId = '2';
        $amount = 1000;
        $transferStatus = 'finished';

        $event = new TransferFinishedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);

       $this->assertIsObject($event);
    }
}
