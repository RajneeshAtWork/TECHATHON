<?php

namespace App\Controllers\Auth;

use App\Core\Auth;
use App\Core\Response;
use App\Core\Session;

class LogoutController
{
    public function logout(): void
    {
        Auth::logout();

        Session::flash(
            'success',
            'You have been logged out successfully.'
        );

        $response = new Response();

        $response->redirect('/TECHATHON/public/login');
    }
}