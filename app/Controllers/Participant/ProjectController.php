<?php

namespace App\Controllers\Participant;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\HackathonRegistration;
use App\Models\Project;
use App\Models\Submission;

class ProjectController extends Controller
{
    public function index(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $projectModel = new Project();

        return $this->view(
            'participant/projects/index',
            [
                'title' => 'My Projects',
                'projects' => $projectModel->getForUser($userId),
            ]
        );
    }


    public function create(int $registrationId): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $registrationModel = new HackathonRegistration();

        $registration =
            $registrationModel->findForUserRegistration(
                $registrationId,
                $userId
            );

        if (!$registration) {
            http_response_code(404);
            exit('404 - Registration Not Found');
        }

        /*
         * For team registrations, only the team leader
         * can create the project.
         */
        if (
            ($registration['registration_type'] ?? '') === 'team'
        ) {
            $teamId = (int) (
                $registration['team_id'] ?? 0
            );

            if (
                !$registrationModel->isTeamLeader(
                    $teamId,
                    $userId
                )
            ) {
                http_response_code(403);
                exit(
                    '403 - Only the team leader can create the project.'
                );
            }
        }

        $projectModel = new Project();

        if (
            $projectModel->existsForRegistration(
                $registrationId
            )
        ) {
            Session::flash(
                'error',
                'A project already exists for this registration.'
            );

            header(
                'Location: /TECHATHON/public/participant/projects'
            );
            exit;
        }

        return $this->view(
            'participant/projects/create',
            [
                'title' => 'Create Project',
                'registration' => $registration,
                'csrfToken' => Csrf::token(),
            ]
        );
    }


    public function store(int $registrationId): void
    {
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $registrationModel =
            new HackathonRegistration();

        $registration =
            $registrationModel->findForUserRegistration(
                $registrationId,
                $userId
            );

        if (!$registration) {
            http_response_code(404);
            exit('404 - Registration Not Found');
        }

        /*
         * For team registrations, only the leader can
         * create the project.
         */
        if (
            ($registration['registration_type'] ?? '') === 'team'
        ) {
            $teamId = (int) (
                $registration['team_id'] ?? 0
            );

            if (
                !$registrationModel->isTeamLeader(
                    $teamId,
                    $userId
                )
            ) {
                http_response_code(403);
                exit(
                    '403 - Only the team leader can create the project.'
                );
            }
        }

        $title =
            trim(
                (string) (
                    $_POST['title'] ?? ''
                )
            );

        $description =
            trim(
                (string) (
                    $_POST['description'] ?? ''
                )
            );

        $githubUrl =
            trim(
                (string) (
                    $_POST['github_url'] ?? ''
                )
            );

        $demoUrl =
            trim(
                (string) (
                    $_POST['demo_url'] ?? ''
                )
            );

        $videoUrl =
            trim(
                (string) (
                    $_POST['video_url'] ?? ''
                )
            );


        if ($title === '') {
            Session::flash(
                'error',
                'Project title is required.'
            );

            $this->redirectToCreate($registrationId);
        }


        if ($description === '') {
            Session::flash(
                'error',
                'Project description is required.'
            );

            $this->redirectToCreate($registrationId);
        }


        if (
            $githubUrl !== '' &&
            !filter_var(
                $githubUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            Session::flash(
                'error',
                'Please enter a valid GitHub URL.'
            );

            $this->redirectToCreate($registrationId);
        }


        if (
            $demoUrl !== '' &&
            !filter_var(
                $demoUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            Session::flash(
                'error',
                'Please enter a valid demo URL.'
            );

            $this->redirectToCreate($registrationId);
        }


        if (
            $videoUrl !== '' &&
            !filter_var(
                $videoUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            Session::flash(
                'error',
                'Please enter a valid video URL.'
            );

            $this->redirectToCreate($registrationId);
        }


        $projectModel = new Project();

        if (
            $projectModel->existsForRegistration(
                $registrationId
            )
        ) {
            Session::flash(
                'error',
                'A project already exists for this registration.'
            );

            header(
                'Location: /TECHATHON/public/participant/projects'
            );
            exit;
        }


        $projectId =
            $projectModel->create(
                (int) $registration['hackathon_id'],
                $registrationId,
                $title,
                $description,
                $githubUrl !== ''
                    ? $githubUrl
                    : null,
                $demoUrl !== ''
                    ? $demoUrl
                    : null,
                $videoUrl !== ''
                    ? $videoUrl
                    : null
            );


        Session::flash(
            'success',
            'Project created successfully.'
        );

        header(
            'Location: /TECHATHON/public/participant/projects/' .
            $projectId
        );
        exit;
    }


    public function show(int $id): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $projectModel = new Project();

        $project = $projectModel->findById($id);

        if (!$project) {
            http_response_code(404);
            exit('404 - Project Not Found');
        }


        $registrationModel =
            new HackathonRegistration();

        $registration =
            $registrationModel->findForUserRegistration(
                (int) $project['registration_id'],
                $userId
            );

        if (!$registration) {
            http_response_code(403);
            exit('403 - Forbidden');
        }


        $submissionModel = new Submission();

        return $this->view(
            'participant/projects/show',
            [
                'title' => $project['title'],
                'project' => $project,
                'registration' => $registration,
                'latestSubmission' =>
                    $submissionModel->findLatestForProject(
                        $id
                    ),
                'submissions' =>
                    $submissionModel->getForProject(
                        $id
                    ),
                'csrfToken' => Csrf::token(),
            ]
        );
    }


    public function submit(int $id): void
    {
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $projectModel = new Project();

        $project = $projectModel->findById($id);

        if (!$project) {
            http_response_code(404);
            exit('404 - Project Not Found');
        }


        $registrationModel =
            new HackathonRegistration();

        $registration =
            $registrationModel->findForUserRegistration(
                (int) $project['registration_id'],
                $userId
            );

        if (!$registration) {
            http_response_code(403);
            exit('403 - Forbidden');
        }


        /*
         * Only the team leader can submit a team project.
         */
        if (
            ($registration['registration_type'] ?? '') === 'team'
        ) {
            $teamId = (int) (
                $registration['team_id'] ?? 0
            );

            if (
                !$registrationModel->isTeamLeader(
                    $teamId,
                    $userId
                )
            ) {
                Session::flash(
                    'error',
                    'Only the team leader can submit the team project.'
                );

                header(
                    'Location: /TECHATHON/public/participant/projects/' .
                    $id
                );
                exit;
            }
        }


        /*
         * Prevent submitting after the hackathon has ended.
         */
        $hackathonEnd =
            $project['hackathon_end'] ?? null;

        if ($hackathonEnd !== null) {
            try {
                $now = new \DateTime();
                $end = new \DateTime($hackathonEnd);

                if ($now > $end) {
                    Session::flash(
                        'error',
                        'The hackathon submission period has ended.'
                    );

                    header(
                        'Location: /TECHATHON/public/participant/projects/' .
                        $id
                    );
                    exit;
                }
            } catch (\Exception $exception) {
                // Leave the date validation to the database data
                // if the stored date cannot be parsed.
            }
        }


        $notes =
            trim(
                (string) (
                    $_POST['submission_notes']
                    ?? ''
                )
            );


        $submissionModel = new Submission();

        $latestSubmission =
            $submissionModel->findLatestForProject($id);


        /*
         * Do not create another submission if the latest
         * submission is already submitted.
         */
        if (
            $latestSubmission &&
            ($latestSubmission['status'] ?? '') === 'submitted'
        ) {
            Session::flash(
                'error',
                'This project has already been submitted.'
            );

            header(
                'Location: /TECHATHON/public/participant/projects/' .
                $id
            );
            exit;
        }


        if (
            $latestSubmission &&
            ($latestSubmission['status'] ?? '') === 'draft'
        ) {
            $submissionModel->submitDraft(
                (int) $latestSubmission['id']
            );

            Session::flash(
                'success',
                'Your project has been submitted successfully.'
            );

            header(
                'Location: /TECHATHON/public/participant/projects/' .
                $id
            );
            exit;
        }


        $submissionModel->createSubmitted(
            $id,
            $notes
        );

        Session::flash(
            'success',
            'Your project has been submitted successfully.'
        );

        header(
            'Location: /TECHATHON/public/participant/projects/' .
            $id
        );
        exit;
    }


    private function redirectToCreate(
        int $registrationId
    ): never {
        header(
            'Location: /TECHATHON/public/participant/registrations/' .
            $registrationId .
            '/project/create'
        );

        exit;
    }
}