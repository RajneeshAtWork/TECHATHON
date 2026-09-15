<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected PDO $db;

    protected string $table;

    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE {$this->primaryKey} = :id
                LIMIT 1";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
        ]);

        $result = $statement->fetch();

        return $result ?: null;
    }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        return $this->db
            ->query($sql)
            ->fetchAll();
    }

    protected function query(
        string $sql,
        array $parameters = []
    ): array {
        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }
}
