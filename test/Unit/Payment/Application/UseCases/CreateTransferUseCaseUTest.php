<?php

namespace App\Tests\Unit\Payment\Application\UseCases;

use App\Authorization\Domain\Interfaces\AuthorizerInterface;
use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\ValueObject\UserType;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Payment\Application\UseCases\CreateTransferUseCase;
use App\Payment\Domain\Entity\Transfer;
use App\Payment\Domain\Repositories\TransferRepositoryInterface;
use App\Payment\Infra\Messaging\Producer\TransferProducer;
use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use App\Shared\Infra\Database\TransactionManager;
use DomainException;
use Hyperf\DbConnection\Db;
use PHPUnit\Framework\TestCase;

class CreateTransferUseCaseUTest extends TestCase
{
    private WalletRepositoryInterface $walletRepository;
    private UserRepositoryInterface $userRepository;
    private TransferRepositoryInterface $transferRepository;
    private TransferProducer $transferProducer;
    private CreateTransferUseCase $useCase;

    private TransactionManager $transactionManager;

    protected function setUp(): void
    {
        $this->walletRepository = $this->createMock(WalletRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->transferRepository = $this->createMock(TransferRepositoryInterface::class);
        $this->transferProducer = $this->createMock(TransferProducer::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->useCase = new CreateTransferUseCase(
            $this->walletRepository,
            $this->userRepository,
            $this->transferRepository,
            $this->transferProducer,
            $this->transactionManager
        );
    }

    public function testThrowsIfSameUser(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Não é possível efetuar transferências entre o mesmo usuário');

        $this->useCase->execute('2', '2', 100);
    }

    public function testThrowsIfPayerCannotTransfer(): void
    {
        $payerUserType = new UserType(UserType::MERCHANT);

        $payer = $this->createMock(User::class);
        $payer->method('getUserType')->willReturn($payerUserType);

        $payee = $this->createMock(User::class);

        $this->userRepository->method('findById')->willReturnMap([
            ['1', $payer],
            ['2', $payee],
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Apenas usuários comuns podem efetuar transferências');

        $this->useCase->execute('1', '2', 100);
    }

    public function testThrowsIfPayerNotFound(): void
    {
        $payee = $this->createMock(User::class);

        $this->userRepository->method('findById')->willReturnMap([
            ['1', $payee],
            ['2', null],
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Pagador não encontrado');

        $this->useCase->execute('1', '2', 100);
    }

    public function testThrowsIfPayeeNotFound(): void
    {
        $payer = $this->createMock(User::class);

        $this->userRepository->method('findById')->willReturnMap([
            ['1', null],
            ['2', $payer],
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Recebedor não encontrado');

        $this->useCase->execute('1', '2', 100);
    }

    public function testThrowsIfInsufficientBalance(): void
    {
        $payerUserType = new UserType(UserType::COMMOM);

        $payer = $this->createMock(User::class);
        $payer->method('getUserType')->willReturn($payerUserType);

        $payee = $this->createMock(User::class);

        $wallet = $this->createMock(Wallet::class);
        $wallet->method('hasSufficientBalance')->with(100)->willReturn(false);

        $this->userRepository->method('findById')->willReturnMap([
            ['1', $payer],
            ['2', $payee],
        ]);

        $this->walletRepository->method('findByUserId')->with('1')->willReturn($wallet);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Saldo insuficiente');

        $this->useCase->execute('2', '1', 100);
    }

    public function testExecuteSuccess(): void
    {
        $payerUserType = new UserType(UserType::COMMOM);

        $payer = $this->createMock(User::class);
        $payer->method('getUserType')->willReturn($payerUserType);

        $payee = $this->createMock(User::class);

        $wallet = $this->createMock(Wallet::class);
        $wallet->method('hasSufficientBalance')->willReturn(true);

        $wallet->expects($this->once())->method('debit')->with(100);

        $this->userRepository->method('findById')->willReturnMap([
            ['1', $payer],
            ['2', $payee],
        ]);

        $this->walletRepository->method('findByUserId')->with('1')->willReturn($wallet);

        $this->walletRepository->expects($this->once())->method('update')->with($wallet);

        $transfer = Transfer::createTransfer('2', '1', 100);

        $this->transferRepository->method('save')->willReturn('123');

        $this->transferRepository->expects($this->once())->method('save')->with($this->isInstanceOf(Transfer::class), '2', '1')->willReturn('123');

        $this->transferProducer->expects($this->once())->method('publishTransferEvent')
            ->with(
                '123',
                '1',
                '2',
                100,
                (string) $transfer->getTransferStatus()
            );

        $result = $this->useCase->execute('2', '1', 100);

        $this->assertIsArray($result);
    }
}
