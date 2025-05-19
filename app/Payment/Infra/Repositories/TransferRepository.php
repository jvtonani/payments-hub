<?php

namespace App\Payment\Infra\Repositories;

use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Models\TransferModel;

class TransferRepository implements TransferRepositoryInterface
{

    public function save(Transfer $transfer, string $payeeId, string $payerId): mixed
    {
        $transfer = $transfer->toArray();
        $model = new TransferModel();
        $model->fill($transfer);
        $model->payer_id = $payerId;
        $model->payee_id = $payeeId;
        $model->save();
        return $model->getKey();
    }

}