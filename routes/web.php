<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\HackathonController;
use App\Controllers\Admin\UserController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\Core\Router;
use App\Middleware\RoleMiddleware;

$router = new Router();

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

$router->get(
    '/register',
    [RegisterController::class, 'show']
);

$router->post(
    '/register',
    [RegisterController::class, 'register']
);

$router->get(
    '/login',
    [LoginController::class, 'show']
);

$router->post(
    '/login',
    [LoginController::class, 'login']
);


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

$router->post(
    '/logout',
    [LogoutController::class, 'logout']
);


/*
|--------------------------------------------------------------------------
| Protected Home Route
|--------------------------------------------------------------------------
*/

$router->get(
    '/',
    [HomeController::class, 'index'],
    [
        \App\Middleware\AuthMiddleware::class
    ]
);


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin',
    [AdminController::class, 'index'],
    [
        [RoleMiddleware::class, ['admin']]
    ]
);


/*
|--------------------------------------------------------------------------
| Admin - User Management
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/users',
    [UserController::class, 'index'],
    [
        [RoleMiddleware::class, ['admin']]
    ]
);


/*
|--------------------------------------------------------------------------
| Admin - Hackathon Management
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/hackathons',
    [HackathonController::class, 'index'],
    [
        [RoleMiddleware::class, ['admin']]
    ]
);


/*
|--------------------------------------------------------------------------
| Admin - Pending Hackathon Approvals
|--------------------------------------------------------------------------
*/

$router->get(
    '/admin/hackathons/pending',
    [HackathonController::class, 'pending'],
    [
        [RoleMiddleware::class, ['admin']]
    ]
);


/*
|--------------------------------------------------------------------------
| Return Router
|--------------------------------------------------------------------------
*/

return $router;