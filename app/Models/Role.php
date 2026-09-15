<?php

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected string $table = 'roles';

    public function findByName(string $name): ?array
    {
        $statement = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE name = :name
             LIMIT 1"
        );

        $statement->execute([
            'name' => $name,
        ]);

        $role = $statement->fetch();

        return $role ?: null;
    }
    
}