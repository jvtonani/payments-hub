<?php

namespace App\Tests\Unit\Payment\Infra\Repositories;

use App\Payment\Domain\Entity\Transfer;
use App\Payment\Infra\Models\TransferModel;
use App\Payment\Infra\Repositories\TransferRepository;
use PHPUnit\Framework\TestCase;

class TransferRepositoryUTest extends TestCase
{
    private TransferModel $transferModel;
    private TransferRepository $repository;

    protected function setUp(): void
    {
        $this->transferModel = $this->createMock(TransferModel::class);
        $this->repository = new TransferRepository($this->transferModel);
    }

    public function testFindByIdReturnsTransfer(): void
    {
        $this->transferModel->method('query')
            ->willReturn([
                [
                    'id' => 1,
                    'payee_id' => '1',
                    'payer_id' => '2',
                    'amount' => 1000,
                    'transfer_status' => 'created',
                ]
            ]);

        $transfer = $this->repository->findById(1);

        $this->assertInstanceOf(Transfer::class, $transfer);
        $this->assertEquals(1, $transfer->toArray()['id']);
    }

    public function testFindByIdReturnsNull(): void
    {
        $transfer = $this->repository->findById(2);

        $this->assertNull($transfer);
    }

    public function testSaveInsertsTransfer(): void
    {
        $transfer = Transfer::createTransfer('1', '2', 1000, 'created');

        $this->transferModel->expects($this->once())
            ->method('save')
            ->with([
                'payer_id' => '2',
                'payee_id' => '1',
                'amount' => 1000,
                'transfer_status' => 'created',
            ])
            ->willReturn(1);

        $id = $this->repository->save($transfer, '1', '2');

        $this->assertEquals(1, $id);
    }

    public function testUpdateTransferStatus(): void
    {
        $transfer = Transfer::createTransfer('1', '2', 1000, 'finished');
        $transfer->setTransferId(99);

        $this->transferModel->expects($this->once())
            ->method('update')
            ->with(99, ['transfer_status' => 'finished'])
            ->willReturn(true);

        $updatedRows = $this->repository->update($transfer);

        $this->assertEquals(1, $updatedRows);
    }
}
