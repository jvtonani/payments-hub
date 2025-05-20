<?php

namespace App\Payment\Application\UseCases;

use App\Payment\Domain\Repositories\TransferRepositoryInterface;

class GetTransferUseCase
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository
    )
    {
    }

    public function perform(int $id): array
    {
        $transfer = $this->transferRepository->findById($id);

        return $transfer->toArray();
    }
}