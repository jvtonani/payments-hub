<?php

namespace App\Onboarding\Application\UseCases;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;

class CreateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository, private WalletRepositoryInterface $walletRepository)
    {

    }

    public function execute(User $user): User
    {
        if($this->userRepository->existsByDocument((string) $user->getUserDocument())) {
            throw new \DomainException('Usuário já existente');
        }

        if($this->userRepository->existsByEmail((string) $user->getEmail())) {
            throw new \DomainException('E-mail já está em uso ');
        }

        $userId = $this->userRepository->save($user);

        $wallet = Wallet::createWallet($userId, 0);

        $this->walletRepository->save($wallet);

        $user->setId($userId);

        return $user;
    }

}