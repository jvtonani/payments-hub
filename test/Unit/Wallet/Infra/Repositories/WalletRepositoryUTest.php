<?php

namespace App\Tests\Unit\Wallet\Infra\Repositories;

use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Infra\Models\WalletModel;
use App\Wallet\Infra\Repositories\WalletRepository;
use PHPUnit\Framework\TestCase;

class WalletRepositoryUTest extends TestCase
{
    public function testSaveWallet()
    {
        $wallet = Wallet::createWallet(1, 1000);

        $walletModel = $this->createMock(WalletModel::class);
        $walletModel->expects($this->once())
            ->method('save')
            ->with($wallet->toArray())
            ->willReturn(1);

        $repository = new WalletRepository($walletModel);

        $this->assertEquals(1, $repository->save($wallet));
    }

    public function testUpdateWallet()
    {
        $wallet = Wallet::createWallet(1, 1500);

        $walletModel = $this->createMock(WalletModel::class);
        $walletModel->expects($this->once())
            ->method('update')
            ->with(1, ['balance' => 1500])
            ->willReturn(true);

        $repository = new WalletRepository($walletModel);

        $this->assertEquals(1, $repository->update($wallet));
    }

    public function testFindByUserIdReturnsWallet()
    {
        $walletData = [
            'user_id' => 1,
            'balance' => 2000
        ];

        $walletModel = $this->createMock(WalletModel::class);
        $walletModel->expects($this->once())
            ->method('query')
            ->willReturn([$walletData]);

        $repository = new WalletRepository($walletModel);
        $wallet = $repository->findByUserId(1);

        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertEquals(1, $wallet->toArray()['user_id']);
        $this->assertEquals(2000, $wallet->toArray()['balance']);
    }

    public function testFindByUserIdReturnsNullWhenNotFound()
    {
        $walletModel = $this->createMock(WalletModel::class);
        $walletModel->expects($this->once())
            ->method('query')
            ->willReturn([]);

        $repository = new WalletRepository($walletModel);
        $wallet = $repository->findByUserId(99);

        $this->assertNull($wallet);
    }
}
