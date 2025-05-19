<?php

namespace App\Wallet\Infra\Repositories;

use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use App\Wallet\Infra\Models\WalletModel;
use Hyperf\DbConnection\Db;

class WalletRepository implements WalletRepositoryInterface
{
    public function update(Wallet $wallet): int
    {
        return Db::table('wallet')
            ->where('user_id', $wallet->toArray()['user_id'])
            ->update([
                'balance' => $wallet->getBalance()->getCurrentAmount()
            ]);
    }

    public function save(Wallet $wallet, string $userId): mixed
    {
        $model = new WalletModel();

        $model->user_id = $userId;
        $model->balance = 0.0;

        $model->save();

        return $model->getKey();
    }

    public function findByUserId(int $userId): ?Wallet
    {
        $model = WalletModel::where('user_id', $userId)
            ->first();

        if ($model === null) {
            return null;
        }
        return $this->toEntity($model);
    }

    private function toEntity(WalletModel $model): Wallet
    {
        $walletToArray = $model->toArray();

        return Wallet::createWallet(
            $walletToArray['user_id'],
            $walletToArray['balance']
        );
    }
}