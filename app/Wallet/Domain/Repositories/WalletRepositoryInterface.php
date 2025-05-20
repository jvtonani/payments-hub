<?php

namespace App\Wallet\Domain\Repositories;

use App\Wallet\Domain\Entity\Wallet;

interface WalletRepositoryInterface
{
    public function update(Wallet $wallet): int;
    public function findByUserId(int $userId): ?Wallet;
    public function save(Wallet $wallet): int;
}