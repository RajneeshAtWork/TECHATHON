<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Hackathon;
use App\Models\Organizer;
use App\Models\OrganizerProject;

class ProjectController extends Controller
{
    /**
     * Display all projects for an organizer's hackathon.
     */
    public function index(int $hackathonId): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            header('Location: /TECHATHON/public/login');
            exit;
        }

        $organizerModel = new Organizer();
        $hackathonModel = new Hackathon();
        $projectModel = new OrganizerProject();

        $organizer = $organizerModel->findByUserId($userId);

        if (!$organizer) {
            http_response_code(403);
            return 'Organizer profile not found.';
        }

        $hackathon = $hackathonModel->findForOrganizer(
            $hackathonId,
            (int) $organizer['id']
        );

        if (!$hackathon) {
            http_response_code(404);
            return 'Hackathon not found.';
        }

        $projects = $projectModel->getForHackathon(
            $hackathonId,
            (int) $organizer['id']
        );

        return $this->view(
            'organizer/hackathons/projects',
            [
                'title' => 'Projects - ' . $hackathon['title'],
                'hackathon' => $hackathon,
                'projects' => $projects,
            ]
        );
    }


    /**
     * Display one project.
     */
    public function show(
        int $hackathonId,
        int $projectId
    ): string {
        $userId = Auth::id();

        if ($userId === null) {
            header('Location: /TECHATHON/public/login');
            exit;
        }

        $organizerModel = new Organizer();
        $hackathonModel = new Hackathon();
        $projectModel = new OrganizerProject();

        $organizer = $organizerModel->findByUserId($userId);

        if (!$organizer) {
            http_response_code(403);
            return 'Organizer profile not found.';
        }

        $hackathon = $hackathonModel->findForOrganizer(
            $hackathonId,
            (int) $organizer['id']
        );

        if (!$hackathon) {
            http_response_code(404);
            return 'Hackathon not found.';
        }

        $project = $projectModel->findForHackathon(
            $hackathonId,
            $projectId,
            (int) $organizer['id']
        );

        if (!$project) {
            http_response_code(404);
            return 'Project not found.';
        }

        $submissions = $projectModel->getSubmissions($projectId);

        $teamMembers = [];

        if (
            ($project['registration_type'] ?? null) === 'team'
            && !empty($project['registration_id'])
        ) {
            $teamMembers = $projectModel->getTeamMembers(
                $hackathonId,
                (int) $project['registration_id'],
                (int) $organizer['id']
            );
        }

        return $this->view(
            'organizer/hackathons/project',
            [
                'title' => 'Project - ' . $project['title'],
                'hackathon' => $hackathon,
                'project' => $project,
                'submissions' => $submissions,
                'teamMembers' => $teamMembers,
            ]
        );
    }
}