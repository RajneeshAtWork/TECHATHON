<?php

use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\HackathonController;
use App\Controllers\Admin\UserController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\Controllers\Organizer\HackathonController as OrganizerHackathonController;
use App\Controllers\Organizer\OrganizerController;
use App\Core\Router;
use App\Middleware\RoleMiddleware;


/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/

$router = new Router();


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

/*
 * Registration page
 */
$router->get(
    '/register',
    [RegisterController::class, 'show']
);

/*
 * Registration submit
 */
$router->post(
    '/register',
    [RegisterController::class, 'register']
);

/*
 * Login page
 */
$router->get(
    '/login',
    [LoginController::class, 'show']
);

/*
 * Login submit
 */
$router->post(
    '/login',
    [LoginController::class, 'login']
);


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

/*
 * Logout
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
| Admin Routes
|--------------------------------------------------------------------------
*/

/*
 * Admin Dashboard
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

/*
 * Users list
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

/*
 * All hackathons
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

/*
 * Pending hackathons
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
| Organizer Routes
|--------------------------------------------------------------------------
*/

/*
 * Organizer Dashboard
 */
$router->get(
    '/organizer',
    [OrganizerController::class, 'index'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer - Create Hackathon
|--------------------------------------------------------------------------
*/

/*
 * Show create hackathon form
 */
$router->get(
    '/organizer/hackathons/create',
    [OrganizerHackathonController::class, 'create'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
 * Create hackathon
 */
$router->post(
    '/organizer/hackathons/create',
    [OrganizerHackathonController::class, 'store'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer - Edit Hackathon
|--------------------------------------------------------------------------
*/

/*
 * Show edit form
 *
 * {id} = hackathon ID
 */
$router->get(
    '/organizer/hackathons/{id}/edit',
    [OrganizerHackathonController::class, 'edit'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
 * Update hackathon
 *
 * {id} = hackathon ID
 */
$router->post(
    '/organizer/hackathons/{id}/edit',
    [OrganizerHackathonController::class, 'update'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer - Submit Hackathon for Admin Approval
|--------------------------------------------------------------------------
*/

/*
 * Submit draft hackathon for approval
 *
 * {id} = hackathon ID
 *
 * Status:
 *
 * draft
 *     ↓
 * pending_approval
 */
$router->post(
    '/organizer/hackathons/{id}/submit',
    [OrganizerHackathonController::class, 'submitForApproval'],
    [
        [RoleMiddleware::class, ['organizer']]
    ]
);


/*
|--------------------------------------------------------------------------
| Return Router
|--------------------------------------------------------------------------
*/

return $router;