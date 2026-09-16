<?php

namespace App\Controllers\Participant;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\HackathonRegistration;
use App\Models\Team;
use App\Models\TeamInvitation;

class ParticipantController extends Controller
{
    public function index(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $registrationModel = new HackathonRegistration();
        $teamModel = new Team();
        $invitationModel = new TeamInvitation();

        $registrations =
            $registrationModel->getForUser($userId);

        $teams =
            $teamModel->getTeamsForUser($userId);

        $pendingInvitations =
            $invitationModel->getPendingForUser($userId);

        return $this->view(
            'participant/dashboard',
            [
                'title' => 'Participant Dashboard',

                'user' => Auth::user(),

                'registrations' => $registrations,

                'teams' => $teams,

                'pendingInvitations' =>
                    $pendingInvitations,

                'registrationCount' =>
                    count($registrations),

                'teamCount' =>
                    count($teams),

                'invitationCount' =>
                    count($pendingInvitations),
            ]
        );
    }
}