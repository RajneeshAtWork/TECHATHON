<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\User;
use App\Models\Role;

class RegisterController extends Controller
{
    public function show(): string
    {
        return $this->view('auth/register', [
            'title' => 'Create Account',
        ]);
    }

    public function register(): void
    {
        $response = new Response();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            Session::flash('error', 'All fields are required.');

            $response->redirect('/TECHATHON/public/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');

            $response->redirect('/TECHATHON/public/register');
        }

        if (strlen($password) < 8) {
            Session::flash(
                'error',
                'Password must be at least 8 characters.'
            );

            $response->redirect('/TECHATHON/public/register');
        }

        if ($password !== $passwordConfirmation) {
            Session::flash('error', 'Passwords do not match.');

            $response->redirect('/TECHATHON/public/register');
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            Session::flash(
                'error',
                'An account with this email already exists.'
            );

            $response->redirect('/TECHATHON/public/register');
        }

        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
        ]);

        $roleModel = new Role();

        $participantRole = $roleModel->findByName('participant');

        if (!$participantRole) {
            throw new \RuntimeException(
                'Participant role was not found.'
            );
        }

        $userModel->assignRole(
            $userId,
            (int) $participantRole['id']
        );

        Session::flash(
            'success',
            'Account created successfully. You can now log in.'
        );

        $response->redirect('/TECHATHON/public/login');
    }
}