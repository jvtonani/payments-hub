<?php

namespace App\Shared\Infra\Database;

use Hyperf\DbConnection\Db;

abstract class BaseModel
{
    protected string $table;

    public function __construct()
    {
        if (!isset($this->table)) {
            throw new \Exception("Obrigatório definir o nome da tabela");
        }
    }

    /**
     * @param string $query Ex: 'SELECT * FROM users WHERE status = :status'
     * @param array $bindings Ex: [':status' => 'active']
     * @return array
     */
    public function query(string $query, array $bindings = []): array
    {
        $questionQuery = preg_replace('/:([a-zA-Z0-9_]+)/', '?', $query);
        $values = [];

        preg_match_all('/:([a-zA-Z0-9_]+)/', $query, $matches);
        foreach ($matches[1] as $key) {
            if (!array_key_exists(":$key", $bindings)) {
                throw new \InvalidArgumentException("Binding :$key não fornecido.");
            }
            $values[] = $bindings[":$key"];
        }

        return array_map(fn($item) => (array) $item, Db::select($questionQuery, $values));
    }
    public function save(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Db::table($this->table)->insertGetId($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        if(isset($data['id'])) {
            unset($data['id']);
        }

        return Db::table($this->table)->where('id', $id)->update($data) > 0;
    }
}
