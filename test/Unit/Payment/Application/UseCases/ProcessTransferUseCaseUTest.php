<?php

namespace App\Tests\Unit\Payment\Application\UseCases;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Payment\Application\UseCases\ProcessTransferUseCase;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Messaging\Producer\TransferProducer;
use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcessTransferUseCaseUTest extends TestCase
{
    private AuthorizerInterface $authorizer;
    private LoggerInterface $logger;
    private TransferRepositoryInterface $transferRepository;
    private WalletRepositoryInterface $walletRepository;
    private TransferProducer $transferProducer;
    private ProcessTransferUseCase $useCase;

    protected function setUp(): void
    {
        $this->authorizer = $this->createMock(AuthorizerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->transferRepository = $this->createMock(TransferRepositoryInterface::class);
        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $this->transferProducer = $this->createMock(TransferProducer::class);

        $this->useCase = new ProcessTransferUseCase(
            $this->authorizer,
            $this->logger,
            $this->transferRepository,
            $this->walletRepository,
            $this->transferProducer
        );
    }

    public function testExecuteAuthorizeFailAndRefund(): void
    {
        $transfer = $this->createMock(Transfer::class);

        $transferArray = [
            'id' => 1,
            'payer_user_id' => '1',
            'payee_user_id' => '2',
            'amount' => 100,
            'transfer_status' => 'failed'
        ];

        $wallet = $this->createMock(Wallet::class);

        $this->authorizer->expects($this->once())->method('isAuthorized')->willReturn(false);

        $transfer->expects($this->once())->method('setStatusFailed');
        $transfer->expects($this->once())->method('toArray')->willReturn($transferArray);

        $this->walletRepository->expects($this->once())
            ->method('findByUserId')
            ->with('1')
            ->willReturn($wallet);

        $wallet->expects($this->once())->method('credit')->with(100);

        $this->walletRepository->expects($this->once())->method('update')->with($wallet);

        $this->transferRepository->expects($this->once())->method('update')->with($transfer);

        $this->transferProducer->expects($this->once())
            ->method('publishTransferFailedEvent')
            ->with(
                1,
                '1',
                '2',
                100,
                'failed'
            );

        $this->useCase->execute($transfer);
    }

    public function testExecuteAuthorizedSuccess(): void
    {
        $transfer = $this->createMock(Transfer::class);
        $wallet = $this->createMock(Wallet::class);

        $transferArray = [
            'id' => 2,
            'payer_user_id' => '1',
            'payee_user_id' => '2',
            'amount' => 200,
            'transfer_status' => 'finished'
        ];

        $this->authorizer->expects($this->once())->method('isAuthorized')->willReturn(true);

        $transfer->expects($this->once())->method('getPayeeUserId')->willReturn('2');
        $transfer->expects($this->once())->method('getTransferBalance')->willReturn(200.0);
        $transfer->expects($this->once())->method('setStatusFinished');
        $transfer->expects($this->once())->method('toArray')->willReturn($transferArray);

        $this->walletRepository->expects($this->once())
            ->method('findByUserId')
            ->with('2')
            ->willReturn($wallet);

        $wallet->expects($this->once())->method('credit')->with(200);
        $this->walletRepository->expects($this->once())->method('update')->with($wallet);

        $this->transferRepository->expects($this->once())->method('update')->with($transfer);

        $this->transferProducer->expects($this->once())
            ->method('publishTransferFinishedEvent')
            ->with(
                2,
                '1',
                '2',
                200,
                'finished'
            );

        $this->useCase->execute($transfer);
    }
}
