<?php

namespace App\Wallet\Interface\Http\Controller;

use App\Shared\Interface\Http\Controller\AbstractController;
use App\Wallet\Application\UseCases\CreditWalletUseCase;
use App\Wallet\Domain\Entity\Wallet;
use Hyperf\HttpServer\Contract\ResponseInterface;

class CreditWalletController extends AbstractController
{
    public function __construct(
        protected ResponseInterface $response,
        private CreditWalletUseCase $creditWalletUseCase,
    ){}

    public function perform(): \Psr\Http\Message\ResponseInterface
    {
        $requestParams = $this->request->all();
        $wallet = Wallet::createWallet($requestParams['user_id'], $requestParams['amount']);
        $updatedWallet = $this->creditWalletUseCase->execute($wallet);

        return $this->response->json($updatedWallet->toArray())->withStatus(201);
    }
}