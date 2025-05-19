<?php

namespace App\Onboarding\Infra\Models;

use Hyperf\DbConnection\Model\Model;

class UserModel extends Model
{
    protected ?string $table = 'user';
    protected string $primaryKey = 'id';
    public bool $incrementing = true;
    protected string $keyType = 'string';
    protected array $fillable = [
        'id', 'name', 'document', 'email', 'password', 'user_type',
    ];
    public bool $timestamps = true;
}
