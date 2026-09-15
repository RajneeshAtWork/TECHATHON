<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Response;

class RoleMiddleware
{
    public static function handle(string ...$roles): void
    {
        if (!Auth::check()) {
            $response = new Response();

            $response->redirect('/TECHATHON/public/login');
        }

        $user = Auth::user();

        if (!$user) {
            Auth::logout();

            $response = new Response();

            $response->redirect('/TECHATHON/public/login');
        }

        $userRoles = $user['roles'] ?? [];

        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return;
            }
        }

        http_response_code(403);

        exit('403 - Access Denied');
    }
}