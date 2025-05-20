<?php

namespace App\Payment\Domain\Repositories;

use App\Payment\Domain\Entity\Transfer;

interface TransferRepositoryInterface
{
    public function save(Transfer $transfer,  string $payeeId, string $payerId): mixed;

    public function update(Transfer $transfer): int;
    public function findById(int $id): ?Transfer;

}