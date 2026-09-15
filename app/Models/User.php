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

    public function create(array $data): int
    {
        $statement = $this->db->prepare(
            "INSERT INTO {$this->table}
            (name, email, password)
            VALUES (:name, :email, :password)"
        );

        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function assignRole(int $userId, int $roleId): void
    {
        $statement = $this->db->prepare(
            "INSERT INTO user_roles (user_id, role_id)
             VALUES (:user_id, :role_id)"
        );

        $statement->execute([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public function getRoles(int $userId): array
    {
        $statement = $this->db->prepare(
            "SELECT r.name
             FROM roles r
             INNER JOIN user_roles ur
                 ON ur.role_id = r.id
             WHERE ur.user_id = :user_id"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll(
            \PDO::FETCH_COLUMN
        );
    }
    public function getAllWithRoles(): array
    {
        $statement = $this->db->query(
            "SELECT
            u.id,
            u.name,
            u.email,
            u.status,
            u.created_at,
            GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ', ') AS roles
         FROM users u
         LEFT JOIN user_roles ur
            ON ur.user_id = u.id
         LEFT JOIN roles r
            ON r.id = ur.role_id
         GROUP BY
            u.id,
            u.name,
            u.email,
            u.status,
            u.created_at
         ORDER BY u.created_at DESC"
        );

        return $statement->fetchAll();
    }
}