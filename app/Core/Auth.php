<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt(
        string $email,
        string $password
    ): bool {
        $userModel = new User();

        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (($user['status'] ?? 'active') !== 'active') {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        Session::regenerate();

        Session::set('user_id', (int) $user['id']);

        return true;
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): ?int
    {
        if (!self::check()) {
            return null;
        }

        return (int) Session::get('user_id');
    }

    public static function user(): ?array
{
    $id = self::id();

    if ($id === null) {
        return null;
    }

    $userModel = new User();

    $user = $userModel->find($id);

    if (!$user) {
        return null;
    }

    $user['roles'] = $userModel->getRoles($id);

    return $user;
}

    public static function logout(): void
    {
        Session::destroy();
    }
}