<?php

namespace App\Controllers\Participant;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Hackathon;
use App\Models\HackathonRegistration;
use DateTime;

class HackathonController extends Controller
{
    public function index(): string
    {
        $hackathonModel = new Hackathon();

        return $this->view(
            'participant/hackathons/index',
            [
                'title' => 'Browse Hackathons',
                'hackathons' => $hackathonModel->getAllWithDetails(),
            ]
        );
    }


    public function show(int $id): string
    {
        $hackathonModel = new Hackathon();
        $registrationModel = new HackathonRegistration();

        $hackathon = $hackathonModel->find($id);

        if (!$hackathon) {
            http_response_code(404);
            exit('404 - Hackathon Not Found');
        }


        /*
         * Participants can only view hackathons that have
         * reached the approved/registration stage.
         */
        if (
            !in_array(
                strtolower(
                    (string) ($hackathon['status'] ?? '')
                ),
                [
                    'approved',
                    'registration_open',
                ],
                true
            )
        ) {
            http_response_code(404);
            exit('404 - Hackathon Not Available');
        }


        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }


        /*
         * ----------------------------------------------------------
         * Existing registration
         * ----------------------------------------------------------
         */
        $registration =
            $registrationModel->findForUser(
                (int) $hackathon['id'],
                $userId
            );


        /*
         * ----------------------------------------------------------
         * Registration window
         * ----------------------------------------------------------
         */
        $registrationWindowOpen = false;

        if (
            !empty($hackathon['registration_start']) &&
            !empty($hackathon['registration_end'])
        ) {
            try {

                $now = new DateTime();

                $registrationStart = new DateTime(
                    $hackathon['registration_start']
                );

                $registrationEnd = new DateTime(
                    $hackathon['registration_end']
                );

                $registrationWindowOpen =
                    $now >= $registrationStart &&
                    $now <= $registrationEnd;

            } catch (\Exception $exception) {

                $registrationWindowOpen = false;
            }
        }


        /*
         * ----------------------------------------------------------
         * User's teams
         * ----------------------------------------------------------
         *
         * We only need teams when the hackathon allows
         * team participation.
         */
        $teams = [];

        $participationType =
            strtolower(
                (string) (
                    $hackathon['participation_type']
                    ?? 'individual'
                )
            );

        if (
            in_array(
                $participationType,
                [
                    'team',
                    'both',
                ],
                true
            )
        ) {
            $teams =
                $registrationModel->getTeamsForUser(
                    $userId
                );
        }


        return $this->view(
            'participant/hackathons/show',
            [
                'title' => $hackathon['title'],
                'hackathon' => $hackathon,

                /*
                 * IMPORTANT:
                 * These variable names match your existing
                 * show.php exactly.
                 */
                'registration' => $registration,
                'registrationWindowOpen' =>
                    $registrationWindowOpen,

                'teams' => $teams,
                'csrfToken' => Csrf::token(),
            ]
        );
    }


    public function register(int $id): void
    {
        /*
         * ----------------------------------------------------------
         * CSRF
         * ----------------------------------------------------------
         */
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }


        /*
         * ----------------------------------------------------------
         * Authentication
         * ----------------------------------------------------------
         */
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }


        $hackathonModel = new Hackathon();
        $registrationModel = new HackathonRegistration();

        $hackathon = $hackathonModel->find($id);

        if (!$hackathon) {
            http_response_code(404);
            exit('404 - Hackathon Not Found');
        }


        /*
         * ----------------------------------------------------------
         * Hackathon status
         * ----------------------------------------------------------
         */
        $status =
            strtolower(
                (string) (
                    $hackathon['status']
                    ?? ''
                )
            );

        if (
            !in_array(
                $status,
                [
                    'approved',
                    'registration_open',
                ],
                true
            )
        ) {
            Session::flash(
                'error',
                'This hackathon is not currently available for registration.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Registration window
         * ----------------------------------------------------------
         */
        if (
            empty($hackathon['registration_start']) ||
            empty($hackathon['registration_end'])
        ) {
            Session::flash(
                'error',
                'Registration dates are not configured correctly.'
            );

            $this->redirectBackToHackathon($id);
        }


        try {

            $now = new DateTime();

            $registrationStart =
                new DateTime(
                    $hackathon['registration_start']
                );

            $registrationEnd =
                new DateTime(
                    $hackathon['registration_end']
                );

        } catch (\Exception $exception) {

            Session::flash(
                'error',
                'Registration dates are invalid.'
            );

            $this->redirectBackToHackathon($id);
        }


        if (
            $now < $registrationStart ||
            $now > $registrationEnd
        ) {
            Session::flash(
                'error',
                'Registration is not currently open.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Participation type
         * ----------------------------------------------------------
         */
        $participationType =
            strtolower(
                (string) (
                    $hackathon['participation_type']
                    ?? 'individual'
                )
            );

        $registrationType =
            strtolower(
                trim(
                    (string) (
                        $_POST['registration_type']
                        ?? ''
                    )
                )
            );


        /*
         * Participant is never allowed to submit "both".
         */
        if (
            !in_array(
                $registrationType,
                [
                    'individual',
                    'team',
                ],
                true
            )
        ) {
            Session::flash(
                'error',
                'Invalid registration type.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Organizer's participation rule
         * ----------------------------------------------------------
         */
        if (
            $participationType === 'individual' &&
            $registrationType !== 'individual'
        ) {
            Session::flash(
                'error',
                'This hackathon only allows individual participation.'
            );

            $this->redirectBackToHackathon($id);
        }


        if (
            $participationType === 'team' &&
            $registrationType !== 'team'
        ) {
            Session::flash(
                'error',
                'This hackathon requires team participation.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Duplicate registration protection
         * ----------------------------------------------------------
         *
         * This also checks whether the participant already belongs
         * to a registered team for this hackathon.
         */
        if (
            $registrationModel->userHasActiveRegistration(
                $id,
                $userId
            )
        ) {
            Session::flash(
                'error',
                'You are already registered for this hackathon.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ==========================================================
         * INDIVIDUAL REGISTRATION
         * ==========================================================
         */
        if (
            $registrationType === 'individual'
        ) {
            $maxParticipants =
                $hackathon['max_participants']
                ?? null;

            $currentParticipants =
                $registrationModel
                    ->countIndividualRegistrations($id);


            /*
             * max_participants is the participant capacity.
             *
             * Team members do NOT consume this capacity when
             * registering as a team.
             */
            if (
                $maxParticipants !== null &&
                $maxParticipants !== '' &&
                $currentParticipants >=
                (int) $maxParticipants
            ) {
                Session::flash(
                    'error',
                    'Individual participant capacity has been reached.'
                );

                $this->redirectBackToHackathon($id);
            }


            $registrationModel->createIndividual(
                $id,
                $userId
            );


            Session::flash(
                'success',
                'You have successfully registered for this hackathon.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ==========================================================
         * TEAM REGISTRATION
         * ==========================================================
         */

        $teamId =
            (int) (
                $_POST['team_id']
                ?? 0
            );


        if ($teamId <= 0) {
            Session::flash(
                'error',
                'Please select a team.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Team membership
         * ----------------------------------------------------------
         */
        if (
            !$registrationModel->userIsTeamMember(
                $teamId,
                $userId
            )
        ) {
            Session::flash(
                'error',
                'You are not a member of the selected team.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Team leader
         * ----------------------------------------------------------
         *
         * Only the leader can register a team for a hackathon.
         */
        if (
            !$registrationModel->isTeamLeader(
                $teamId,
                $userId
            )
        ) {
            Session::flash(
                'error',
                'Only the team leader can register the team.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Team size
         * ----------------------------------------------------------
         */
        $memberCount =
            $registrationModel->getTeamMemberCount(
                $teamId
            );

        $minTeamSize =
            $hackathon['min_team_size']
            ?? null;

        $maxTeamSize =
            $hackathon['max_team_size']
            ?? null;


        if (
            $minTeamSize !== null &&
            $minTeamSize !== '' &&
            $memberCount < (int) $minTeamSize
        ) {
            Session::flash(
                'error',
                'Your team must have at least ' .
                (int) $minTeamSize .
                ' members.'
            );

            $this->redirectBackToHackathon($id);
        }


        if (
            $maxTeamSize !== null &&
            $maxTeamSize !== '' &&
            $memberCount > (int) $maxTeamSize
        ) {
            Session::flash(
                'error',
                'Your team cannot have more than ' .
                (int) $maxTeamSize .
                ' members.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Team capacity
         * ----------------------------------------------------------
         *
         * IMPORTANT:
         * A team consumes ONE team slot regardless of whether it
         * has 2, 3, 4, etc. members.
         */
        $maxTeams =
            $hackathon['max_teams']
            ?? null;

        $currentTeams =
            $registrationModel
                ->countTeamRegistrations($id);


        if (
            $maxTeams !== null &&
            $maxTeams !== '' &&
            $currentTeams >= (int) $maxTeams
        ) {
            Session::flash(
                'error',
                'Team capacity has been reached.'
            );

            $this->redirectBackToHackathon($id);
        }


        /*
         * ----------------------------------------------------------
         * Create team registration
         * ----------------------------------------------------------
         */
        $registrationModel->createTeam(
            $id,
            $teamId,
            $userId
        );


        Session::flash(
            'success',
            'Your team has been successfully registered for this hackathon.'
        );

        $this->redirectBackToHackathon($id);
    }


    /**
     * Redirect back to the participant hackathon details page.
     */
    private function redirectBackToHackathon(
        int $id
    ): never {
        header(
            'Location: /TECHATHON/public/participant/hackathons/' . $id
        );

        exit;
    }

    public function registrations(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $registrationModel =
            new HackathonRegistration();

        return $this->view(
            'participant/registrations/index',
            [
                'title' => 'My Registrations',
                'registrations' =>
                    $registrationModel->getForUser($userId),
            ]
        );
    }

}