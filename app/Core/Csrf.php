<?php

namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        $token = Session::get('_csrf_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));

            Session::set('_csrf_token', $token);
        }

        return $token;
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = Session::get('_csrf_token');

        if (
            !$sessionToken ||
            !$token
        ) {
            return false;
        }

        return hash_equals(
            $sessionToken,
            $token
        );
    }
}