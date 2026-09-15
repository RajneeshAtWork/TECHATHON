<?php

namespace App\Controllers\Auth;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;

class LoginController extends Controller
{
    public function show(): string
    {
        return $this->view('auth/login', [
            'title' => 'Login',
        ]);
    }

    public function login(): void
    {
        $response = new Response();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            Session::flash(
                'error',
                'Email and password are required.'
            );

            $response->redirect('/TECHATHON/public/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash(
                'error',
                'Please enter a valid email address.'
            );

            $response->redirect('/TECHATHON/public/login');
        }

        if (!Auth::attempt($email, $password)) {
            Session::flash(
                'error',
                'Invalid email or password.'
            );

            $response->redirect('/TECHATHON/public/login');
        }

        Session::flash(
            'success',
            'Login successful.'
        );

        $response->redirect('/TECHATHON/public/');
    }
}