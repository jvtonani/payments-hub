<?php

namespace App\Wallet\Infra\Repositories;

use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use App\Wallet\Infra\Models\WalletModel;

class WalletRepository implements WalletRepositoryInterface
{
    public function __construct(private WalletModel $walletModel)
    {

    }

    public function update(Wallet $wallet): int
    {
        $walletArray = $wallet->toArray();
        return $this->walletModel->update($walletArray['user_id'], ['balance'  => $walletArray['balance']]);
    }

    public function save(Wallet $wallet): int
    {
        return $this->walletModel->save($wallet->toArray());
    }

    public function findByUserId(int $userId): ?Wallet
    {
        $query = 'SELECT * FROM wallet WHERE user_id = :user_id';
        $wallet = $this->walletModel->query($query, [':user_id' => $userId]);

        if(empty($wallet)){
            return null;
        }

        return $this->toEntity($wallet[0]);
    }

    private function toEntity(Array $wallet): Wallet
    {
        return Wallet::createWallet(
            $wallet['user_id'],
            $wallet['balance']
        );
    }
}