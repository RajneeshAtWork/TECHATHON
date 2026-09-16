<?php

namespace App\Controllers\Participant;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Team;
use App\Models\TeamInvitation;

class TeamController extends Controller
{
    /**
     * My Teams.
     */
    public function index(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);

            return '401 - Unauthorized';
        }

        $teamModel = new Team();

        return $this->view(
            'participant/teams/index',
            [
                'title' => 'My Teams',
                'teams' =>
                    $teamModel->getTeamsForUser(
                        $userId
                    ),
                'csrfToken' => Csrf::token(),
            ]
        );
    }

    /**
     * Create Team form.
     */
    public function create(): string
    {
        if (Auth::id() === null) {
            http_response_code(401);

            return '401 - Unauthorized';
        }

        return $this->view(
            'participant/teams/create',
            [
                'title' => 'Create Team',
                'csrfToken' => Csrf::token(),
            ]
        );
    }

    /**
     * Store a new team.
     */
    public function store(): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $name =
            trim(
                (string) (
                    $_POST['name'] ?? ''
                )
            );

        $description =
            trim(
                (string) (
                    $_POST['description'] ?? ''
                )
            );

        if ($name === '') {
            Session::flash(
                'error',
                'Team name is required.'
            );

            $this->redirect(
                '/TECHATHON/public/participant/teams/create'
            );
        }

        if (mb_strlen($name) > 150) {
            Session::flash(
                'error',
                'Team name must not exceed 150 characters.'
            );

            $this->redirect(
                '/TECHATHON/public/participant/teams/create'
            );
        }

        if (mb_strlen($description) > 5000) {
            Session::flash(
                'error',
                'Team description is too long.'
            );

            $this->redirect(
                '/TECHATHON/public/participant/teams/create'
            );
        }

        $teamModel = new Team();

        $teamId =
            $teamModel->createTeam(
                $userId,
                $name,
                $description !== ''
                    ? $description
                    : null
            );

        Session::flash(
            'success',
            'Team created successfully.'
        );

        $this->redirect(
            '/TECHATHON/public/participant/teams/' .
            $teamId
        );
    }

    /**
     * Display one team.
     */
    public function show(int $id): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);

            return '401 - Unauthorized';
        }

        $teamModel = new Team();

        $team =
            $teamModel->findForUser(
                $id,
                $userId
            );

        if (!$team) {
            http_response_code(404);

            return '404 - Team Not Found';
        }

        $invitationModel =
            new TeamInvitation();

        $invitationModel->markExpiredForTeam($id);

        return $this->view(
            'participant/teams/show',
            [
                'title' =>
                    $team['name'] . ' - Team',

                'team' => $team,

                'members' =>
                    $teamModel->getMembers($id),

                'isLeader' =>
                    $teamModel->isLeader(
                        $id,
                        $userId
                    ),

                'memberCount' =>
                    $teamModel->getMemberCount($id),

                'pendingInvitations' =>
                    $invitationModel
                        ->getPendingForTeam($id),

                'csrfToken' => Csrf::token(),
            ]
        );
    }

    /**
     * Send a team invitation.
     */
    public function invite(int $id): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $teamModel = new Team();

        if (
            !$teamModel->isLeader(
                $id,
                $userId
            )
        ) {
            http_response_code(403);
            exit(
                '403 - Only the team leader can send invitations.'
            );
        }

        $email =
            strtolower(
                trim(
                    (string) (
                        $_POST['email'] ?? ''
                    )
                )
            );

        if (
            $email === ''
            || !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            Session::flash(
                'error',
                'Please enter a valid participant email address.'
            );

            $this->redirectToTeam($id);
        }

        $invitedUser =
            $teamModel->findUserByEmail($email);

        if (!$invitedUser) {
            Session::flash(
                'error',
                'No registered user was found with that email address.'
            );

            $this->redirectToTeam($id);
        }

        $invitedUserId =
            (int) $invitedUser['id'];

        if ($invitedUserId === $userId) {
            Session::flash(
                'error',
                'You are already the leader of this team.'
            );

            $this->redirectToTeam($id);
        }

        if (
            ($invitedUser['status'] ?? 'active')
            !== 'active'
        ) {
            Session::flash(
                'error',
                'This user account is not active.'
            );

            $this->redirectToTeam($id);
        }

        if (
            !$this->isParticipant(
                $invitedUserId
            )
        ) {
            Session::flash(
                'error',
                'Only participant accounts can be invited to a team.'
            );

            $this->redirectToTeam($id);
        }

        if (
            $teamModel->isMember(
                $id,
                $invitedUserId
            )
        ) {
            Session::flash(
                'error',
                'This participant is already a member of the team.'
            );

            $this->redirectToTeam($id);
        }

        $invitationModel =
            new TeamInvitation();

        $invitationModel->markExpiredForTeam($id);

        if (
            $invitationModel->hasPendingInvitation(
                $id,
                $invitedUserId
            )
        ) {
            Session::flash(
                'error',
                'A pending invitation already exists for this participant.'
            );

            $this->redirectToTeam($id);
        }

        $invitationModel->createInvitation(
            $id,
            $invitedUserId,
            $userId
        );

        Session::flash(
            'success',
            'Team invitation sent successfully to '
            . $invitedUser['name']
            . '.'
        );

        $this->redirectToTeam($id);
    }

    /**
     * Redirect helper.
     */
    private function redirectToTeam(
        int $teamId
    ): never {
        $this->redirect(
            '/TECHATHON/public/participant/teams/' .
            $teamId
        );
    }

    /**
     * Redirect.
     */
    private function redirect(
        string $url
    ): never {
        header(
            'Location: ' . $url
        );

        exit;
    }

    /**
     * Check whether the user has the participant role.
     */
    private function isParticipant(
        int $userId
    ): bool {
        $database =
            \App\Core\Database::connect();

        $statement = $database->prepare(
            "SELECT COUNT(*)
             FROM user_roles ur
             INNER JOIN roles r
                ON r.id = ur.role_id
             WHERE ur.user_id = :user_id
               AND r.name = 'participant'"
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}