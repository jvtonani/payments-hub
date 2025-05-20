<?php

namespace App\Onboarding\Interface\Http\Controllers;

use App\Onboarding\Application\UseCases\CreateUserUseCase;
use App\Onboarding\Domain\Entity\User;
use App\Shared\Interface\Http\Controller\AbstractController;
use Hyperf\HttpServer\Contract\ResponseInterface;

class CreateUserController extends AbstractController
{
    public function __construct(
        protected ResponseInterface $response,
        private CreateUserUseCase $createUser
    )
    {
    }

    public function perform(): \Psr\Http\Message\ResponseInterface
    {
        $params = $this->request->all();

        $user = User::createUser(
            $params['document'],
            $params['name'],
            $params['email'],
            $params['user_type'],
            $params['password'],
            $params['cellphone'],
            null
        );

        $user = $this->createUser->execute($user);

        $returnData = $user->toArray();

        return $this->response->json($returnData)->withStatus(201);

    }
}