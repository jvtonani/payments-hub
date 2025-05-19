<?php

namespace App\Payment\Interface\Http\Controllers;

use App\Payment\Domain\UseCases\CreateTransferUseCase;
use App\Shared\Interface\Http\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class CreateTransferController extends AbstractController
{
    public function __construct(private CreateTransferUseCase $createTransferUseCase, private LoggerInterface $logger)
    {
    }

    public function perform(): mixed
    {
        $data = $this->request->all();

        $this->logger->info('Está logando', []);
        return $this->createTransferUseCase->execute($data['payee'], $data['payer'], $data['value']);
    }
}