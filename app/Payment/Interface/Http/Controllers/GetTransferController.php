<?php

namespace App\Payment\Interface\Http\Controllers;

use App\Payment\Application\UseCases\GetTransferUseCase;
use App\Shared\Interface\Http\Controller\AbstractController;
use Hyperf\HttpServer\Contract\ResponseInterface;

class GetTransferController extends AbstractController
{
    public function __construct(
        protected ResponseInterface $response,
        private GetTransferUseCase $getTransferUseCase,
    )
    {
    }

    public function perform(int $id): \Psr\Http\Message\ResponseInterface
    {
        $transfer = $this->getTransferUseCase->perform($id);
        return $this->response->json($transfer)->withStatus(200);
    }
}