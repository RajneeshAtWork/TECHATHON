<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE email = :email
             LIMIT 1"
        );

        $statement->execute([
            'email' => $email,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }
}