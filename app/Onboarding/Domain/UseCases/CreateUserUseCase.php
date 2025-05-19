<?php

namespace App\Onboarding\Domain\UseCases;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;

class CreateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
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

        $user->setId($userId);

        return $user;
    }

}