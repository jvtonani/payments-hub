<?php

declare(strict_types=1);

namespace App\Tests\Integration\Payment;

use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use function Hyperf\Support\make;

class GetTransferControllerITest extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = make(Client::class);

        $container = ApplicationContext::getContainer();

        $transfer = $this->createMock(Transfer::class);
        $transfer->method('toArray')->willReturn([
            'id' => 1,
            'payer_id' => 'payer-id',
            'payee_id' => 'payee-id',
            'amount' => 150,
            'status' => 'completed',
        ]);

        $transferRepository = $this->createMock(TransferRepositoryInterface::class);
        $transferRepository->method('findById')->with(1)->willReturn($transfer);

        $container->set(TransferRepositoryInterface::class, $transferRepository);
    }

    public function testGetTransferById()
    {
        $data = $this->client->get('/transfer/1', [], [
            'Content-Type' => 'application/json'
        ]);

        $this->assertEquals(1, $data['id']);
        $this->assertEquals('payer-id', $data['payer_id']);
        $this->assertEquals('payee-id', $data['payee_id']);
        $this->assertEquals(150, $data['amount']);
        $this->assertEquals('completed', $data['status']);
    }
}
