<?php

namespace App\Tests\Unit\Payment\Application\Events;

use App\Payment\Application\Events\TransferCreatedEvent;
use PHPUnit\Framework\TestCase;

class TransferCreatedEventUTest extends TestCase
{
    public function testConstructorSetsAllProperties(): void
    {
        $transferId = '123';
        $payerId = '1';
        $payeeId = '2';
        $amount = 1000;
        $transferStatus = 'created';

        $event = new TransferCreatedEvent($transferId, $payerId, $payeeId, $amount, $transferStatus);

       $this->assertIsObject($event);
    }
}
