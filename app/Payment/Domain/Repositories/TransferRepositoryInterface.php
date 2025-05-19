<?php

namespace App\Payment\Domain\Repositories;

use App\Payment\Domain\Entity\Transfer;

interface TransferRepositoryInterface
{
    public function save(Transfer $transfer,  string $payeeId, string $payerId): mixed;
}