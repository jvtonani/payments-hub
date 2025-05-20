<?php

namespace App\Notification\Model;

use App\Shared\Infra\Database\BaseModel;

class User extends BaseModel
{
    protected string $table = 'user';
    public function findById(string $userId): ?array
    {
        $query = 'SELECT * FROM user WHERE id = :id';
        $users = $this->query($query, [':id' => $userId]);

        if(empty($users)){
            return null;
        }

        return $users[0];
    }
}