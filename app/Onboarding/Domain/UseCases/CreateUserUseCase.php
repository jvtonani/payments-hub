<?php

namespace App\Onboarding\Domain\UseCases;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Wallet\Domain\Entity\Wallet;
use App\Wallet\Domain\Repositories\WalletRepositoryInterface;
use App\Wallet\Infra\Repositories\WalletRepository;

class CreateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository, private WalletRepositoryInterface $walletRepository)
    {

    }

    public function execute(User $user): User
    {
        if($this->userRepository->existsByCpf((string) $user->getUserDocument())) {
            throw new \DomainException('Usuário já existente');
        }

        if($this->userRepository->existsByEmail((string) $user->getEmail())) {
            throw new \DomainException('E-mail já está em uso ');
        }

        $userId = $this->userRepository->save($user);

        $wallet = Wallet::createWallet((string) $user->getUserDocument(), 0);

        $this->walletRepository->save($wallet, $userId);

        $user->setId($userId);

        return $user;
    }

}