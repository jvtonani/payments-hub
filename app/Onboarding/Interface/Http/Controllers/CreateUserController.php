<?php

namespace App\Onboarding\Interface\Http\Controllers;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\UseCases\CreateUserUseCase;
use App\Shared\Interface\Http\Controller\AbstractController;

class CreateUserController extends AbstractController
{
    public function __construct(
        private CreateUserUseCase $createUser
    )
    {
    }

    public function perform(): array
    {
        $params = $this->request->all();

        $user = User::createUser($params['document'], $params['name'], $params['email'], $params['user_type'], $params['password'], null);

        $user = $this->createUser->execute($user);

        return $user->toArray();
    }
}