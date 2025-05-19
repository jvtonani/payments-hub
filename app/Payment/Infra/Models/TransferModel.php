<?php

namespace App\Payment\Infra\Models;

use Hyperf\DbConnection\Model\Model;

class TransferModel extends Model
{
    protected ?string $table = 'transfer';
    protected string $primaryKey = 'id';
    public bool $incrementing = true;
    protected string $keyType = 'string';
    protected array $fillable = [
        'id','payer_id', 'payee_id', 'amount', 'status',
    ];
    public bool $timestamps = true;
}