<?php

namespace App\Payment\Infra\Repositories;

use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Models\TransferModel;

class TransferRepository implements TransferRepositoryInterface
{

    public function __construct(private TransferModel $transferModel)
    {
    }

    public function save(Transfer $transfer, string $payeeId, string $payerId): mixed
    {
        $transferArray = $transfer->toArray();

        $dataToInsert['payer_id'] = $payerId;
        $dataToInsert['payee_id'] = $payeeId;
        $dataToInsert['amount'] = $transferArray['amount'];
        $dataToInsert['transfer_status'] = $transferArray['transfer_status'];


        return $this->transferModel->save($dataToInsert);
    }

    public function update(Transfer $transfer): int
    {
        $transferArray = $transfer->toArray();
        return $this->transferModel->update($transferArray['id'], ['transfer_status' => $transferArray['transfer_status']]);
    }

}