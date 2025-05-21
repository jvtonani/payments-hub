<?php

namespace App\Tests\Unit\Onboarding\Application\UseCases;

use App\Onboarding\Application\UseCases\CreateUserUseCase;
use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserUseCaseUTest extends TestCase
{
    private function makeUser(): User
    {
        return User::createUser(
            '12345678900',
            'nome',
            'email@example.com',
            'merchant',
            'pass',
            '55999999999',
            1
        );
    }
    public function testShouldThrowExceptionWhenDocumentExists()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário já existente');

        $user = $this->makeUser();

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $walletRepository = $this->createMock(WalletRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method('existsByDocument')
            ->willReturn(true);

        $useCase = new CreateUserUseCase($userRepository, $walletRepository);
        $useCase->execute($user);
    }

    public function testShouldThrowExceptionWhenEmailExists()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('E-mail já está em uso');

        $user = $this->makeUser();

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $walletRepository = $this->createMock(WalletRepositoryInterface::class);

        $userRepository->expects($this->once())
            ->method('existsByDocument')
            ->willReturn(false);

        $userRepository->expects($this->once())
            ->method('existsByEmail')
            ->willReturn(true);

        $useCase = new CreateUserUseCase($userRepository, $walletRepository);
        $useCase->execute($user);
    }
}
