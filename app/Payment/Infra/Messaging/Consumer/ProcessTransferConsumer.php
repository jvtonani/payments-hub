<?php

namespace App\Payment\Infra\Messaging\Consumer;

use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\UseCases\ProcessTransferUseCase;
use App\Shared\Domain\ValueObject\TransferStatus;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Result;
use Psr\Log\LoggerInterface;

#[Consumer(
    exchange: "transfer_status_exchange",
    queue: "process_transfer_queue",
    name: "ProcessTransferConsumer",
    nums: 2
)]
class ProcessTransferConsumer extends ConsumerMessage
{
    public function __construct(private LoggerInterface $logger, private ProcessTransferUseCase $processTransferUseCase)
    {
        $this->type = Type::FANOUT;
    }

    public function consume($data): Result
    {
        if($data['transfer_status'] != TransferStatus::CREATED) {
            $this->logger->info('Transação já processada', $data);
            return Result::ACK;
        }

        try {
            $transfer = Transfer::createTransfer(
                $data['payee_id'],
                $data['payer_id'],
                $data['amount'],
                $data['transfer_status'],
            );

            $transfer->setTransferId($data['transfer_id']);

            $this->processTransferUseCase->execute($transfer);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        return Result::ACK;
    }
}