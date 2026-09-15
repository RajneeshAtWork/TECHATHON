<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\App;
use App\Core\Database;

App::boot();

$name = 'TECHATHON Admin';
$email = 'admin@techathon.local';

echo "Creating TECHATHON admin account...\n";

$password = readline("Enter admin password: ");

if (strlen($password) < 8) {
    exit("Password must be at least 8 characters.\n");
}

$db = Database::connect();

$statement = $db->prepare(
    "SELECT id FROM users WHERE email = :email LIMIT 1"
);

$statement->execute([
    'email' => $email,
]);

$existingUser = $statement->fetch();

if ($existingUser) {
    echo "Admin account already exists.\n";
    exit;
}

try {
    $db->beginTransaction();

    $statement = $db->prepare(
        "INSERT INTO users
        (name, email, password)
        VALUES
        (:name, :email, :password)"
    );

    $statement->execute([
        'name' => $name,
        'email' => $email,
        'password' => password_hash(
            $password,
            PASSWORD_DEFAULT
        ),
    ]);

    $userId = (int) $db->lastInsertId();

    $statement = $db->prepare(
        "SELECT id
         FROM roles
         WHERE name = 'admin'
         LIMIT 1"
    );

    $statement->execute();

    $role = $statement->fetch();

    if (!$role) {
        throw new RuntimeException(
            'Admin role was not found.'
        );
    }

    $statement = $db->prepare(
        "INSERT INTO user_roles
        (user_id, role_id)
        VALUES
        (:user_id, :role_id)"
    );

    $statement->execute([
        'user_id' => $userId,
        'role_id' => (int) $role['id'],
    ]);

    $db->commit();

    echo "\nAdmin account created successfully.\n";
    echo "Email: {$email}\n";

} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    echo "\nFailed to create admin account.\n";
    echo $e->getMessage() . "\n";
}