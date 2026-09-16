<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Organizer;
use App\Models\OrganizerTeam;
use App\Models\Hackathon;

class TeamController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Team List
    |--------------------------------------------------------------------------
    */

    public function index(int $hackathonId): string
    {
        $organizerModel = new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );


        if (!$organizer) {

            http_response_code(403);

            return 'Organizer profile not found.';
        }


        $hackathonModel = new Hackathon();

        $hackathon =
            $hackathonModel->findForOrganizer(
                $hackathonId,
                $organizer['id']
            );


        if (!$hackathon) {

            http_response_code(404);

            return 'Hackathon not found.';
        }


        $teamModel = new OrganizerTeam();

        $teams =
            $teamModel->getForHackathon(
                $hackathonId,
                $organizer['id']
            );


        return $this->view(
            'organizer/hackathons/teams',
            [
                'title' =>
                    'Teams - '
                    . ($hackathon['title'] ?? 'Hackathon'),

                'organizer' =>
                    $organizer,

                'hackathon' =>
                    $hackathon,

                'teams' =>
                    $teams,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Team Details
    |--------------------------------------------------------------------------
    */

    public function show(
        int $hackathonId,
        int $teamId
    ): string {

        $organizerModel = new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );


        if (!$organizer) {

            http_response_code(403);

            return 'Organizer profile not found.';
        }


        $hackathonModel = new Hackathon();

        $hackathon =
            $hackathonModel->findForOrganizer(
                $hackathonId,
                $organizer['id']
            );


        if (!$hackathon) {

            http_response_code(404);

            return 'Hackathon not found.';
        }


        $teamModel = new OrganizerTeam();

        $team =
            $teamModel->findForHackathon(
                $hackathonId,
                $teamId,
                $organizer['id']
            );


        if (!$team) {

            http_response_code(404);

            return 'Team not found.';
        }


        $members =
            $teamModel->getMembers(
                $hackathonId,
                $teamId,
                $organizer['id']
            );


        return $this->view(
            'organizer/hackathons/team',
            [
                'title' =>
                    'Team - '
                    . ($team['name'] ?? 'Team'),

                'organizer' =>
                    $organizer,

                'hackathon' =>
                    $hackathon,

                'team' =>
                    $team,

                'members' =>
                    $members,
            ]
        );
    }
}