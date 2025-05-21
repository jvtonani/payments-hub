<?php

namespace App\Tests\Unit\Payment\Infra\Messaging\Consumer;

use App\Payment\Application\UseCases\ProcessTransferUseCase;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Infra\Messaging\Consumer\ProcessTransferConsumer;
use App\Shared\Domain\ValueObject\TransferStatus;
use Hyperf\Amqp\Result;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcessTransferConsumerUTest extends TestCase
{
    private LoggerInterface $logger;
    private ProcessTransferUseCase $useCase;
    private ProcessTransferConsumer $consumer;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->useCase = $this->createMock(ProcessTransferUseCase::class);

        $this->consumer = new ProcessTransferConsumer($this->logger, $this->useCase);
    }

    public function testConsumeReturnsAckWhenStatusIsNotCreated(): void
    {
        $data = [
            'transfer_id' => '123',
            'payer_id' => 'payer',
            'payee_id' => 'payee',
            'amount' => 1000,
            'transfer_status' => 'finished',
        ];

        $result = $this->consumer->consume($data);

        $this->assertSame(Result::ACK, $result);
    }

    public function testConsumeStatusCreated(): void
    {
        $data = [
            'transfer_id' => '123',
            'payer_id' => 'payer',
            'payee_id' => 'payee',
            'amount' => 1000,
            'transfer_status' => TransferStatus::CREATED,
        ];

        $this->useCase
            ->expects($this->once())
            ->method('execute');

        $result = $this->consumer->consume($data);

        $this->assertSame(Result::ACK, $result);
    }
}
