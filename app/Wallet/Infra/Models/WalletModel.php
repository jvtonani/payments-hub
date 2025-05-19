<?php

namespace App\Wallet\Infra\Models;

use Hyperf\DbConnection\Model\Model;

class WalletModel extends Model
{
    protected ?string $table = 'wallet';
    protected string $primaryKey = 'id';
    public bool $incrementing = true;
    protected string $keyType = 'string';
    protected array $fillable = [
        'id', 'user_id', 'balance'
    ];
    public bool $timestamps = true;
}