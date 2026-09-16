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

use App\Controllers\Participant\HackathonController as ParticipantHackathonController;
use App\Controllers\Participant\InvitationController;
use App\Controllers\Participant\TeamController;
use App\Controllers\Participant\ParticipantController;

use App\Controllers\Participant\ProjectController;

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
 * Registration
 */
$router->get(
    '/register',
    [RegisterController::class, 'show']
);

$router->post(
    '/register',
    [RegisterController::class, 'register']
);


/*
 * Login
 */
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
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);


/*
 * Admin User Management
 */
$router->get(
    '/admin/users',
    [UserController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);


/*
 * Admin Hackathon Management
 */
$router->get(
    '/admin/hackathons',
    [HackathonController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);


/*
 * Pending Hackathon Approvals
 */
$router->get(
    '/admin/hackathons/pending',
    [HackathonController::class, 'pending'],
    [
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Admin Hackathon Approval Routes
|--------------------------------------------------------------------------
*/

/*
 * Approve Hackathon
 */
$router->post(
    '/admin/hackathons/{id}/approve',
    [HackathonController::class, 'approve'],
    [
        [
            RoleMiddleware::class,
            ['admin']
        ]
    ]
);


/*
 * Reject Hackathon
 */
$router->post(
    '/admin/hackathons/{id}/reject',
    [HackathonController::class, 'reject'],
    [
        [
            RoleMiddleware::class,
            ['admin']
        ]
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
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * Create Hackathon - Form
 */
$router->get(
    '/organizer/hackathons/create',
    [OrganizerHackathonController::class, 'create'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * Create Hackathon - Submit
 */
$router->post(
    '/organizer/hackathons/create',
    [OrganizerHackathonController::class, 'store'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * Edit Hackathon - Form
 */
$router->get(
    '/organizer/hackathons/{id}/edit',
    [OrganizerHackathonController::class, 'edit'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * Edit Hackathon - Submit
 */
$router->post(
    '/organizer/hackathons/{id}/edit',
    [OrganizerHackathonController::class, 'update'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * Submit Hackathon For Admin Approval
 */
$router->post(
    '/organizer/hackathons/{id}/submit',
    [OrganizerHackathonController::class, 'submitForApproval'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);



/*
|--------------------------------------------------------------------------
| Participant Project Routes
|--------------------------------------------------------------------------
*/

/*
 * My Projects
 */
$router->get(
    '/participant/projects',
    [ProjectController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Create Project
 */
$router->get(
    '/participant/registrations/{id}/project/create',
    [ProjectController::class, 'create'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Save Project
 */
$router->post(
    '/participant/registrations/{id}/project/create',
    [ProjectController::class, 'store'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * View Project
 */
$router->get(
    '/participant/projects/{id}',
    [ProjectController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Submit Project
 */
$router->post(
    '/participant/projects/{id}/submit',
    [ProjectController::class, 'submit'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);

/*
|--------------------------------------------------------------------------
| Participant Dashboard
|--------------------------------------------------------------------------
*/

$router->get(
    '/participant',
    [ParticipantController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);

/*
 * Browse Hackathons
 */
$router->get(
    '/participant/hackathons',
    [ParticipantHackathonController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Hackathon Details
 */
$router->get(
    '/participant/hackathons/{id}',
    [ParticipantHackathonController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Register For Hackathon
 */
$router->post(
    '/participant/hackathons/{id}/register',
    [ParticipantHackathonController::class, 'register'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Participant Registration Routes
|--------------------------------------------------------------------------
*/

/*
 * My Registrations
 */
$router->get(
    '/participant/registrations',
    [ParticipantHackathonController::class, 'registrations'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Participant Team Routes
|--------------------------------------------------------------------------
*/

/*
 * My Teams
 */
$router->get(
    '/participant/teams',
    [TeamController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Create Team - Form
 */
$router->get(
    '/participant/teams/create',
    [TeamController::class, 'create'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Create Team - Submit
 */
$router->post(
    '/participant/teams/create',
    [TeamController::class, 'store'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * View Team
 */
$router->get(
    '/participant/teams/{id}',
    [TeamController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Send Team Invitation
 */
$router->post(
    '/participant/teams/{id}/invitations/create',
    [TeamController::class, 'invite'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Remove Team Member
 */
$router->post(
    '/participant/teams/{id}/members/remove',
    [TeamController::class, 'removeMember'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Participant Team Invitation Routes
|--------------------------------------------------------------------------
*/

/*
 * View Pending Invitations
 */
$router->get(
    '/participant/invitations',
    [InvitationController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Accept Team Invitation
 */
$router->post(
    '/participant/invitations/{id}/accept',
    [InvitationController::class, 'accept'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
 * Reject Team Invitation
 */
$router->post(
    '/participant/invitations/{id}/reject',
    [InvitationController::class, 'reject'],
    [
        [
            RoleMiddleware::class,
            ['participant']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Return Router
|--------------------------------------------------------------------------
*/

return $router;