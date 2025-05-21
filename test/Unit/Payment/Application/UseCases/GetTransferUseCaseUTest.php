<?php

namespace App\Tests\Unit\Payment\Application\UseCases;

use App\Payment\Application\UseCases\GetTransferUseCase;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetTransferUseCaseUTest extends TestCase
{
    public function testPerformReturnsTransferAsArray(): void
    {
        $transferId = 10;
        $expectedArray = [
            'id' => $transferId,
            'payer_user_id' => '2',
            'payee_user_id' => '1',
            'amount' => 500,
            'transfer_status' => 'finished',
        ];

        $transferRepository = $this->createMock(TransferRepositoryInterface::class);
        $transfer = $this->createMock(Transfer::class);

        $transferRepository->expects($this->once())
            ->method('findById')
            ->with($transferId)
            ->willReturn($transfer);

        $transfer->expects($this->once())
            ->method('toArray')
            ->willReturn($expectedArray);

        $useCase = new GetTransferUseCase($transferRepository);

        $result = $useCase->perform($transferId);

        $this->assertEquals($expectedArray, $result);
    }
}
