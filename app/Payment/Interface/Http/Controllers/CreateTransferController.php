<?php

namespace App\Payment\Interface\Http\Controllers;

use App\Payment\Application\UseCases\CreateTransferUseCase;
use App\Request\CreateTransferRequest;
use App\Shared\Interface\Http\Controller\AbstractController;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Log\LoggerInterface;

class CreateTransferController extends AbstractController
{
    public function __construct(protected ResponseInterface $response, private CreateTransferUseCase $createTransferUseCase, private LoggerInterface $logger)
    {
    }

    public function perform(CreateTransferRequest $request): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->validated();
        $this->logger->info("Inicio da transferência");
        $returnData = $this->createTransferUseCase->execute($data['payee'], $data['payer'], $data['value']);

        return $this->response->json($returnData)->withStatus(201);
    }
}