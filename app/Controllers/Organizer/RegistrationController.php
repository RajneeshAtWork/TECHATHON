<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Organizer;
use App\Models\OrganizerRegistration;

class RegistrationController extends Controller
{
    /**
     * Display registrations for one organizer-owned hackathon.
     */
    public function index(int $hackathonId): string
    {
        $organizerModel =
            new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );

        if (!$organizer) {
            http_response_code(403);
            return 'Organizer profile not found.';
        }


        $registrationModel =
            new OrganizerRegistration();

        $registrations =
            $registrationModel->getForHackathon(
                $hackathonId,
                (int) $organizer['id']
            );


        return $this->view(
            'organizer/hackathons/registrations',
            [
                'title' => 'Hackathon Registrations',
                'registrations' => $registrations,
                'hackathonId' => $hackathonId,
            ]
        );
    }


    /**
     * Display one team registration and its members.
     */
    public function team(int $registrationId): string
    {
        $organizerModel =
            new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );

        if (!$organizer) {
            http_response_code(403);
            return 'Organizer profile not found.';
        }


        $registrationModel =
            new OrganizerRegistration();

        $registration =
            $registrationModel->findForOrganizer(
                $registrationId,
                (int) $organizer['id']
            );


        if (!$registration) {
            http_response_code(404);
            return 'Registration not found.';
        }


        if (
            ($registration['registration_type'] ?? '')
            !== 'team'
        ) {
            http_response_code(400);
            return 'This registration is not a team registration.';
        }


        $members =
            $registrationModel->getTeamMembers(
                $registrationId,
                (int) $organizer['id']
            );


        return $this->view(
            'organizer/hackathons/team',
            [
                'title' => 'Team Details',
                'registration' => $registration,
                'members' => $members,
            ]
        );
    }
}