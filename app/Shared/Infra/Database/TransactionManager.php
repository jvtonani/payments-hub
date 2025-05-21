<?php

namespace App\Shared\Infra\Database;

use Hyperf\DbConnection\Db;

class TransactionManager
{
    public function begin(): void
    {
        Db::beginTransaction();
    }

    public function commit(): void
    {
        Db::commit();
    }

    public function rollback(): void
    {
        Db::rollBack();
    }
}