<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Response;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            $response = new Response();

            $response->redirect('/TECHATHON/public/login');
        }
    }
}