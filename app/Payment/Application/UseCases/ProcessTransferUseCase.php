<?php

namespace App\Payment\Application\UseCases;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Messaging\Producer\TransferProducer;
use App\Shared\Domain\ValueObject\TransferStatus;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use Psr\Log\LoggerInterface;

class ProcessTransferUseCase
{
    public function __construct(
        public AuthorizerInterface $authorizer,
        private LoggerInterface $logger,
        private TransferRepositoryInterface $transferRepository,
        private WalletRepositoryInterface $walletRepository,
        private TransferProducer $transferProducer
    )
    {

    }

    public function execute(Transfer $transfer): void
    {
        if(!$this->isTransferWasAlreadyProccessed($transfer)){
            $this->logger->info('Transferência em status inadequado para processamento');
            return;
        }

        if(!$this->authorizer->isAuthorized()) {
            $this->processTransferFailed($transfer);
            $this->logger->info('Transferência falhou, pagador foi estornado');
            return;
        };

        $this->processTransferFinished($transfer);
        $this->logger->info('Transferência sucesso. Demais serviços foram notificados');

    }

    private function processTransferFailed (Transfer $transfer): void
    {
        $transfer->setStatusFailed();
        $this->transferRepository->update($transfer);
        $transferArray = $transfer->toArray();

        $payerWallet = $this->walletRepository->findByUserId($transferArray['payer_user_id']);
        $payerWallet->credit($transferArray['amount']);

        $this->walletRepository->update($payerWallet);

        $this->transferProducer->publishTransferFailedEvent($transferArray['id'], $transferArray['payer_user_id'], $transferArray['payee_user_id'], $transferArray['amount'], $transferArray['transfer_status']);
    }

    private function processTransferFinished(Transfer $transfer): void
    {
        $payeeWallet = $this->walletRepository->findByUserId($transfer->getPayeeUserId());

        $payeeWallet->credit($transfer->getTransferBalance());

        $this->walletRepository->update($payeeWallet);

        $transfer->setStatusFinished();
        $this->transferRepository->update($transfer);

        $transferArray = $transfer->toArray();

        $this->transferProducer->publishTransferFinishedEvent($transferArray['id'], $transferArray['payer_user_id'], $transferArray['payee_user_id'], $transferArray['amount'], $transferArray['transfer_status']);
    }

    private function isTransferWasAlreadyProccessed(Transfer $transfer)
    {
        $transferToArray = $transfer->toArray();
        $hasTransfer = $this->transferRepository->findById($transferToArray['id']);

        $transferStatus = !is_null($hasTransfer) && ($hasTransfer->getTransferStatus() != TransferStatus::CREATED);

        if($transferStatus) {
            return false;
        }

        return true;
    }
}