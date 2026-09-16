<?php

namespace App\Controllers\Participant;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\TeamInvitation;

class InvitationController extends Controller
{
    /**
     * Display my pending team invitations.
     */
    public function index(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);

            return '401 - Unauthorized';
        }

        $invitationModel =
            new TeamInvitation();

        return $this->view(
            'participant/invitations/index',
            [
                'title' => 'Team Invitations',

                'invitations' =>
                    $invitationModel
                        ->getPendingForUser(
                            $userId
                        ),

                'csrfToken' => Csrf::token(),
            ]
        );
    }

    /**
     * Accept an invitation.
     */
    public function accept(int $id): void
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

        $invitationModel =
            new TeamInvitation();

        $accepted =
            $invitationModel->accept(
                $id,
                $userId
            );

        if (!$accepted) {
            Session::flash(
                'error',
                'This invitation is no longer valid or has expired.'
            );

            $this->redirect();
        }

        Session::flash(
            'success',
            'You have joined the team successfully.'
        );

        $this->redirect();
    }

    /**
     * Reject an invitation.
     */
    public function reject(int $id): void
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

        $invitationModel =
            new TeamInvitation();

        $rejected =
            $invitationModel->reject(
                $id,
                $userId
            );

        if (!$rejected) {
            Session::flash(
                'error',
                'This invitation is no longer available.'
            );

            $this->redirect();
        }

        Session::flash(
            'success',
            'Team invitation rejected.'
        );

        $this->redirect();
    }

    /**
     * Redirect to invitation list.
     */
    private function redirect(): never
    {
        header(
            'Location: /TECHATHON/public/participant/invitations'
        );

        exit;
    }
}