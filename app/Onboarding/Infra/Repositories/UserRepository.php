<?php

namespace App\Onboarding\Infra\Repositories;

use App\Onboarding\Domain\Entity\User;
use App\Onboarding\Domain\Repositories\UserRepositoryInterface;
use App\Onboarding\Infra\Models\UserModel;

class UserRepository implements UserRepositoryInterface
{

    public function findById(string $id): ?User
    {
        $model = UserModel::find($id);
        if ($model === null) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function findByEmailOrCpf(string $identifier): ?User
    {
        $model = UserModel::where('email', $identifier)
            ->orWhere('document', $identifier)
            ->first();

        if ($model === null) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function save(User $user): mixed
    {
        $user = $user->toArray();

        $model = new UserModel();

        $model->name = $user['name'];
        $model->document = $user['document_number'];
        $model->email = $user['email'];
        $model->password = $user['password'];
        $model->user_type = $user['user_type'];
        $model->person_type = $user['person_type'];
        $model->document_type = $user['document_type'];

        $model->save();

        return $model->getKey();
    }

    public function existsByCpf(string $cpf): bool
    {
        return UserModel::where('document', $cpf)->exists();
    }

    public function existsByEmail(string $email): bool
    {
        return UserModel::where('email', $email)->exists();
    }

    private function toEntity(UserModel $model): User
    {
        $userToArray = $model->toArray();

        return User::createUser(
            $userToArray['document'],
            $userToArray['name'],
            $userToArray['email'],
            $userToArray['user_type'],
            $userToArray['password'],
            $userToArray['id'],
        );
    }
}
