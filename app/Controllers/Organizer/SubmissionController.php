<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Hackathon;
use App\Models\Organizer;
use App\Models\OrganizerSubmission;

class SubmissionController extends Controller
{
    /**
     * Display all submissions for an organizer's hackathon.
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
        $submissionModel = new OrganizerSubmission();

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

        $submissions = $submissionModel->getForHackathon(
            $hackathonId,
            (int) $organizer['id']
        );

        return $this->view(
            'organizer/hackathons/submissions',
            [
                'title' => 'Submissions - ' . $hackathon['title'],
                'hackathon' => $hackathon,
                'submissions' => $submissions,
            ]
        );
    }


    /**
     * Display one submission.
     */
    public function show(
        int $hackathonId,
        int $submissionId
    ): string {
        $userId = Auth::id();

        if ($userId === null) {
            header('Location: /TECHATHON/public/login');
            exit;
        }

        $organizerModel = new Organizer();
        $hackathonModel = new Hackathon();
        $submissionModel = new OrganizerSubmission();

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

        $submission = $submissionModel->findForHackathon(
            $hackathonId,
            $submissionId,
            (int) $organizer['id']
        );

        if (!$submission) {
            http_response_code(404);
            return 'Submission not found.';
        }

        $projectSubmissions =
            $submissionModel->getProjectSubmissions(
                (int) $submission['project_id']
            );

        $teamMembers = [];

        if (
            ($submission['registration_type'] ?? null) === 'team'
            && !empty($submission['registration_id'])
        ) {
            $teamMembers = $submissionModel->getTeamMembers(
                $hackathonId,
                (int) $submission['registration_id'],
                (int) $organizer['id']
            );
        }

        return $this->view(
            'organizer/hackathons/submission',
            [
                'title' => 'Submission - Version '
                    . (int) $submission['version'],
                'hackathon' => $hackathon,
                'submission' => $submission,
                'projectSubmissions' => $projectSubmissions,
                'teamMembers' => $teamMembers,
            ]
        );
    }
}