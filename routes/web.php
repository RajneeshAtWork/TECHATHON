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
use App\Controllers\Organizer\RegistrationController;
use App\Controllers\Organizer\TeamController as OrganizerTeamController;
use App\Controllers\Organizer\ProjectController as OrganizerProjectController;
use App\Controllers\Organizer\SubmissionController as OrganizerSubmissionController;

use App\Controllers\Participant\HackathonController as ParticipantHackathonController;
use App\Controllers\Participant\InvitationController;
use App\Controllers\Participant\TeamController;
use App\Controllers\Participant\ParticipantController;
use App\Controllers\Participant\ProjectController;

use App\Controllers\Judge\JudgeController;
use App\Controllers\Judge\SubmissionController;

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
 * Registration - Form
 */
$router->get(
    '/register',
    [RegisterController::class, 'show']
);


/*
 * Registration - Submit
 */
$router->post(
    '/register',
    [RegisterController::class, 'register']
);


/*
 * Login - Form
 */
$router->get(
    '/login',
    [LoginController::class, 'show']
);


/*
 * Login - Submit
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
|--------------------------------------------------------------------------
| Organizer Hackathon Registration Routes
|--------------------------------------------------------------------------
*/

/*
 * View Hackathon Registrations
 */
$router->get(
    '/organizer/hackathons/{id}/registrations',
    [RegistrationController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * View Registered Team From Registration
 */
$router->get(
    '/organizer/registrations/{id}/team',
    [RegistrationController::class, 'team'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer Hackathon Creation Routes
|--------------------------------------------------------------------------
*/

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
|--------------------------------------------------------------------------
| Organizer Hackathon Editing Routes
|--------------------------------------------------------------------------
*/

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
| Organizer Hackathon Management
|--------------------------------------------------------------------------
*/

/*
 * Hackathon Management Dashboard
 */
$router->get(
    '/organizer/hackathons/{id}/manage',
    [OrganizerHackathonController::class, 'manage'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer Team Management
|--------------------------------------------------------------------------
*/

/*
 * View Participating Teams
 */
$router->get(
    '/organizer/hackathons/{id}/teams',
    [OrganizerTeamController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * View Registered Team Details
 */
$router->get(
    '/organizer/hackathons/{id}/teams/{teamId}',
    [OrganizerTeamController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer Project Management
|--------------------------------------------------------------------------
*/

/*
 * View Hackathon Projects
 */
$router->get(
    '/organizer/hackathons/{id}/projects',
    [OrganizerProjectController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * View Project Details
 */
$router->get(
    '/organizer/hackathons/{id}/projects/{projectId}',
    [OrganizerProjectController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Organizer Submission Management
|--------------------------------------------------------------------------
*/

/*
 * View Hackathon Submissions
 */
$router->get(
    '/organizer/hackathons/{id}/submissions',
    [OrganizerSubmissionController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);


/*
 * View Submission Details
 */
$router->get(
    '/organizer/hackathons/{id}/submissions/{submissionId}',
    [OrganizerSubmissionController::class, 'show'],
    [
        [
            RoleMiddleware::class,
            ['organizer']
        ]
    ]
);



/*
|--------------------------------------------------------------------------
| Participant Routes
|--------------------------------------------------------------------------
*/

/*
 * Participant Dashboard
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
|--------------------------------------------------------------------------
| Participant Hackathon Routes
|--------------------------------------------------------------------------
*/

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
 * Create Project - Form
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
 * Create Project - Submit
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
| Judge Routes
|--------------------------------------------------------------------------
*/

/*
 * Judge Dashboard
 */
$router->get(
    '/judge',
    [JudgeController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['judge']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Judge Submission Routes
|--------------------------------------------------------------------------
*/

/*
 * View Assigned Hackathon Submissions
 */
$router->get(
    '/judge/hackathons/{id}/submissions',
    [SubmissionController::class, 'index'],
    [
        [
            RoleMiddleware::class,
            ['judge']
        ]
    ]
);


/*
 * Evaluate Submission - Form
 */
$router->get(
    '/judge/submissions/{id}/evaluate',
    [SubmissionController::class, 'evaluate'],
    [
        [
            RoleMiddleware::class,
            ['judge']
        ]
    ]
);


/*
 * Save Evaluation
 */
$router->post(
    '/judge/submissions/{id}/evaluate',
    [SubmissionController::class, 'store'],
    [
        [
            RoleMiddleware::class,
            ['judge']
        ]
    ]
);


/*
|--------------------------------------------------------------------------
| Return Router
|--------------------------------------------------------------------------
*/

return $router;