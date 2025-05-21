<?php

namespace App\Wallet\Application\UseCases;

use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;

class CreditWalletUseCase
{
    public function __construct(private WalletRepositoryInterface $walletRepository)
    {
    }

    public function execute(Wallet $wallet): Wallet
    {
        $walletArray = $wallet->toArray();
        $currentWallet = $this->walletRepository->findByUserId($walletArray['user_id']);

        $newAmount = $walletArray['balance'] + $currentWallet->getBalance()->getCurrentAmount();

        $updatedWallet = Wallet::createWallet($walletArray['user_id'], $newAmount);

        $this->walletRepository->update($updatedWallet);

        return $updatedWallet;
    }
}