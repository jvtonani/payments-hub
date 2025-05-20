<?php

namespace App\Payment\Application\UseCases;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Messaging\Producer\TransferProducer;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;

class CreateTransferUseCase
{
    public function __construct(
        private WalletRepositoryInterface $walletRepository,
        private UserRepositoryInterface $userRepository,
        private TransferRepositoryInterface $transferRepository,
        private AuthorizerInterface $authorizer,
        private TransferProducer $transferProducer,
    )
    {
    }

    public function execute(string $payeeId, string $payerId, int $amount): mixed
    {
        if ($payeeId === $payerId){
            throw new \DomainException('Não é possível efetuar transferências entre o mesmo usuário');
        }

        $payee =  $this->userRepository->findById($payeeId);
        $payer =  $this->userRepository->findById($payerId);

        if(is_null($payer)){
            throw new \DomainException('Pagador não encontrado');
        }

        if(!$payer->getUserType()->canTransfer()){
            throw new \DomainException('Apenas usuários comuns podem efetuar transferências');
        }

        if(is_null($payee)){
            throw new \DomainException('Recebedor não encontrado');
        }

        $payerWallet = $this->walletRepository->findByUserId($payerId);

        if(!$payerWallet->hasSufficientBalance($amount)){
            throw new \DomainException('Saldo insuficiente');
        }

        $payerWallet->debit($amount);
        $this->walletRepository->update($payerWallet);
        $transfer = Transfer::createTransfer(
            $payeeId,
            $payerId,
            $amount
        );

        $transferId = $this->transferRepository->save($transfer, $payeeId, $payerId);
        $transfer->setTransferId($transferId);

        $this->transferProducer->publishTransferEvent($transferId, $payerId, $payeeId, $amount, (string) $transfer->getTransferStatus());

        return $transfer->toArray();
    }

    public function transferIsAuthorized(): bool
    {
        return $this->authorizer->isAuthorized();
    }

}