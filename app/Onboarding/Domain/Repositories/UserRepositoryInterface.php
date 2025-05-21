<?php

namespace App\Onboarding\Domain\Repositories;

use App\Onboarding\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function save(User $user): mixed;
    public function existsByDocument(string $cpf): bool;
    public function existsByEmail(string $email): bool;
}