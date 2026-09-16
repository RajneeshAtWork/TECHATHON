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

        $email =
            trim(
                $_POST['email'] ?? ''
            );

        $password =
            $_POST['password'] ?? '';


        /*
        |--------------------------------------------------------------------------
        | Required fields
        |--------------------------------------------------------------------------
        */

        if (
            $email === '' ||
            $password === ''
        ) {
            Session::flash(
                'error',
                'Email and password are required.'
            );

            $response->redirect(
                '/TECHATHON/public/login'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Email validation
        |--------------------------------------------------------------------------
        */

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            Session::flash(
                'error',
                'Please enter a valid email address.'
            );

            $response->redirect(
                '/TECHATHON/public/login'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::attempt(
                $email,
                $password
            )
        ) {
            Session::flash(
                'error',
                'Invalid email or password.'
            );

            $response->redirect(
                '/TECHATHON/public/login'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Get authenticated user and roles
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            Auth::logout();

            Session::flash(
                'error',
                'Unable to load your account.'
            );

            $response->redirect(
                '/TECHATHON/public/login'
            );

            return;
        }


        $roles =
            $user['roles'] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Success message
        |--------------------------------------------------------------------------
        */

        Session::flash(
            'success',
            'Login successful.'
        );


        /*
        |--------------------------------------------------------------------------
        | Role-based dashboard redirect
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                'admin',
                $roles,
                true
            )
        ) {
            $response->redirect(
                '/TECHATHON/public/admin'
            );

            return;
        }


        if (
            in_array(
                'organizer',
                $roles,
                true
            )
        ) {
            $response->redirect(
                '/TECHATHON/public/organizer'
            );

            return;
        }


        /*
         * Judge dashboard will be added later.
         *
         * For now, judges go to the protected home page
         * rather than a route that does not exist yet.
         */
        if (
            in_array(
                'judge',
                $roles,
                true
            )
        ) {
            $response->redirect(
                '/TECHATHON/public/judge'
            );

            return;
        }


        if (
            in_array(
                'participant',
                $roles,
                true
            )
        ) {
            $response->redirect(
                '/TECHATHON/public/participant'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Invalid / unsupported role
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        Session::flash(
            'error',
            'Your account does not have a valid application role.'
        );

        $response->redirect(
            '/TECHATHON/public/login'
        );
    }
}