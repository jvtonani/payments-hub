<?php

namespace App\Onboarding\Infra\Repositories;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Onboarding\Infra\Models\UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private UserModel $userModel)
    {
    }

    public function findById(string $id): ?User
    {
        $query = 'SELECT * FROM user WHERE id = :id';
        $users = $this->userModel->query($query, [':id' => $id]);

        if(empty($users)){
            return null;
        }

        return $this->toEntity($users[0]);
    }

    public function findByEmailOrCpf(string $identifier): ?User
    {
        $model = UserModel::where('email', $identifier)
            ->orWhere('document_number', $identifier)
            ->first();

        if ($model === null) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function save(User $user): mixed
    {
        return $this->userModel->save($user->toArray());
    }

    public function existsByDocument(string $document): bool
    {
        $query =  'SELECT * FROM user WHERE document_number = :document';

        return (bool) $this->userModel->query($query, [':document' => $document]);
    }

    public function existsByEmail(string $email): bool
    {
        $query = 'SELECT * FROM user WHERE email = :email';

        return (bool) $this->userModel->query($query, [':email' => $email]);
    }

    private function toEntity(Array $userResult): User
    {
        return User::createUser(
            $userResult['document_number'],
            $userResult['name'],
            $userResult['email'],
            $userResult['user_type'],
            $userResult['password'],
            $userResult['cellphone'],
            $userResult['id'],
        );
    }
}
