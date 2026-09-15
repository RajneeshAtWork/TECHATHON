<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\Core\Router;
use App\Middleware\RoleMiddleware;

$router = new Router();

$router->get(
    '/',
    [HomeController::class, 'index'],
    [\App\Middleware\AuthMiddleware::class]
);

$router->get('/register', [RegisterController::class, 'show']);
$router->post('/register', [RegisterController::class, 'register']);

$router->get('/login', [LoginController::class, 'show']);
$router->post('/login', [LoginController::class, 'login']);

$router->post('/logout', [LogoutController::class, 'logout']);

$router->get(
    '/admin',
    [AdminController::class, 'index'],
    [
        [RoleMiddleware::class, ['admin']]
    ]
);

return $router;