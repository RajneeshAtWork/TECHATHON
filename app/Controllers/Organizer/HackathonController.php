<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Response;
use App\Core\Session;
use App\Models\Hackathon;
use App\Models\Organizer;

class HackathonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Hackathon
    |--------------------------------------------------------------------------
    */

    public function create(): string
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


        $db = Database::connect();

        $statement = $db->query(
            "SELECT
                id,
                name
             FROM categories
             ORDER BY name ASC"
        );

        $categories =
            $statement->fetchAll();


        return $this->view(
            'organizer/hackathons/create',
            [
                'organizer' => $organizer,
                'categories' => $categories,
                'csrfToken' => Csrf::token(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Hackathon
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {

            http_response_code(419);

            exit('Invalid CSRF token.');

        }


        $organizerModel = new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );

        if (!$organizer) {

            http_response_code(403);

            exit('Organizer profile not found.');

        }


        /*
        |--------------------------------------------------------------------------
        | Read Form Data
        |--------------------------------------------------------------------------
        */

        $categoryId =
            $this->nullableInteger(
                $_POST['category_id'] ?? null
            );

        $title =
            trim(
                $_POST['title'] ?? ''
            );

        $description =
            trim(
                $_POST['description'] ?? ''
            );

        $rules =
            trim(
                $_POST['rules'] ?? ''
            );

        $requirements =
            trim(
                $_POST['requirements'] ?? ''
            );

        $participationType =
            trim(
                $_POST['participation_type']
                ?? 'individual'
            );

        $minTeamSize =
            $this->nullableInteger(
                $_POST['min_team_size'] ?? null
            );

        $maxTeamSize =
            $this->nullableInteger(
                $_POST['max_team_size'] ?? null
            );

        $maxTeams =
            $this->nullableInteger(
                $_POST['max_teams'] ?? null
            );

        $maxParticipants =
            $this->nullableInteger(
                $_POST['max_participants'] ?? null
            );

        $registrationStart =
            $this->normalizeDate(
                $_POST['registration_start'] ?? null
            );

        $registrationEnd =
            $this->normalizeDate(
                $_POST['registration_end'] ?? null
            );

        $hackathonStart =
            $this->normalizeDate(
                $_POST['hackathon_start'] ?? null
            );

        $hackathonEnd =
            $this->normalizeDate(
                $_POST['hackathon_end'] ?? null
            );

        $submissionDeadline =
            $this->normalizeDate(
                $_POST['submission_deadline'] ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if ($title === '') {

            exit(
                $this->createError(
                    'Hackathon title is required.'
                )
            );

        }


        if ($description === '') {

            exit(
                $this->createError(
                    'Hackathon description is required.'
                )
            );

        }


        if ($categoryId === null) {

            exit(
                $this->createError(
                    'Please select a category.'
                )
            );

        }


        $allowedParticipationTypes = [
            'individual',
            'team',
            'both',
        ];

        if (
            !in_array(
                $participationType,
                $allowedParticipationTypes,
                true
            )
        ) {

            exit(
                $this->createError(
                    'Invalid participation type.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Team Validation
        |--------------------------------------------------------------------------
        */

        if (
            $participationType === 'team'
            || $participationType === 'both'
        ) {

            if ($minTeamSize === null) {

                exit(
                    $this->createError(
                        'Minimum team size is required for team participation.'
                    )
                );

            }


            if ($maxTeamSize === null) {

                exit(
                    $this->createError(
                        'Maximum team size is required for team participation.'
                    )
                );

            }


            if ($minTeamSize < 1) {

                exit(
                    $this->createError(
                        'Minimum team size must be at least 1.'
                    )
                );

            }


            if ($maxTeamSize < $minTeamSize) {

                exit(
                    $this->createError(
                        'Maximum team size cannot be smaller than minimum team size.'
                    )
                );

            }

        } else {

            $minTeamSize = null;
            $maxTeamSize = null;
            $maxTeams = null;

        }


        /*
        |--------------------------------------------------------------------------
        | Team Capacity
        |--------------------------------------------------------------------------
        */

        if (
            (
                $participationType === 'team'
                || $participationType === 'both'
            )
            && $maxTeams !== null
            && $maxTeams < 1
        ) {

            exit(
                $this->createError(
                    'Maximum teams must be at least 1.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Participant Capacity
        |--------------------------------------------------------------------------
        */

        if (
            $maxParticipants !== null
            && $maxParticipants < 1
        ) {

            exit(
                $this->createError(
                    'Maximum participants must be at least 1.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Date Validation
        |--------------------------------------------------------------------------
        */

        if (
            !$registrationStart
            || !$registrationEnd
            || !$hackathonStart
            || !$hackathonEnd
            || !$submissionDeadline
        ) {

            exit(
                $this->createError(
                    'All hackathon dates are required.'
                )
            );

        }


        if (
            strtotime($registrationEnd)
            < strtotime($registrationStart)
        ) {

            exit(
                $this->createError(
                    'Registration end must be after registration start.'
                )
            );

        }


        if (
            strtotime($hackathonStart)
            < strtotime($registrationEnd)
        ) {

            exit(
                $this->createError(
                    'Hackathon start must be on or after registration end.'
                )
            );

        }


        if (
            strtotime($hackathonEnd)
            < strtotime($hackathonStart)
        ) {

            exit(
                $this->createError(
                    'Hackathon end must be after hackathon start.'
                )
            );

        }


        if (
            strtotime($submissionDeadline)
            < strtotime($hackathonEnd)
        ) {

            exit(
                $this->createError(
                    'Submission deadline must be on or after hackathon end.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug =
            $this->generateUniqueSlug(
                $title
            );


        /*
        |--------------------------------------------------------------------------
        | Insert Hackathon
        |--------------------------------------------------------------------------
        */

        $db = Database::connect();

        $statement = $db->prepare(
            "INSERT INTO hackathons (
                organizer_id,
                category_id,
                title,
                slug,
                description,
                rules,
                requirements,
                participation_type,
                min_team_size,
                max_team_size,
                max_teams,
                max_participants,
                registration_start,
                registration_end,
                hackathon_start,
                hackathon_end,
                submission_deadline,
                status
            )
            VALUES (
                :organizer_id,
                :category_id,
                :title,
                :slug,
                :description,
                :rules,
                :requirements,
                :participation_type,
                :min_team_size,
                :max_team_size,
                :max_teams,
                :max_participants,
                :registration_start,
                :registration_end,
                :hackathon_start,
                :hackathon_end,
                :submission_deadline,
                'draft'
            )"
        );


        $statement->execute([
            'organizer_id' =>
                $organizer['id'],

            'category_id' =>
                $categoryId,

            'title' =>
                $title,

            'slug' =>
                $slug,

            'description' =>
                $description,

            'rules' =>
                $rules !== ''
                    ? $rules
                    : null,

            'requirements' =>
                $requirements !== ''
                    ? $requirements
                    : null,

            'participation_type' =>
                $participationType,

            'min_team_size' =>
                $minTeamSize,

            'max_team_size' =>
                $maxTeamSize,

            'max_teams' =>
                $maxTeams,

            'max_participants' =>
                $maxParticipants,

            'registration_start' =>
                $registrationStart,

            'registration_end' =>
                $registrationEnd,

            'hackathon_start' =>
                $hackathonStart,

            'hackathon_end' =>
                $hackathonEnd,

            'submission_deadline' =>
                $submissionDeadline,
        ]);


        Session::flash(
            'success',
            'Hackathon created successfully as a draft.'
        );


        $response = new Response();

        $response->redirect(
            '/TECHATHON/public/organizer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Hackathon
    |--------------------------------------------------------------------------
    */

    public function edit(int $id): string
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


        $hackathonModel =
            new Hackathon();

        $hackathon =
            $hackathonModel->findForOrganizer(
                $id,
                $organizer['id']
            );


        if (!$hackathon) {

            http_response_code(404);

            return 'Hackathon not found.';

        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Only DRAFT hackathons can be edited.
        |
        | Once submitted for approval, the status becomes
        | pending_approval and editing is permanently locked.
        |--------------------------------------------------------------------------
        */

        if (
            ($hackathon['status'] ?? '')
            !== 'draft'
        ) {

            http_response_code(403);

            return
                'This hackathon can no longer be edited because it has already been submitted for approval.';

        }


        /*
        |--------------------------------------------------------------------------
        | 24-Hour Editing Window
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $hackathon['created_at']
            )
        ) {

            http_response_code(403);

            return 'This hackathon cannot be edited.';

        }


        try {

            $createdAt =
                new \DateTimeImmutable(
                    $hackathon['created_at']
                );

        } catch (\Exception $exception) {

            http_response_code(500);

            return 'Invalid hackathon creation date.';

        }


        $editDeadline =
            $createdAt->modify(
                '+24 hours'
            );

        $now =
            new \DateTimeImmutable();


        if ($now > $editDeadline) {

            http_response_code(403);

            return
                'The 24-hour editing period for this hackathon has expired.';

        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $db = Database::connect();

        $statement = $db->query(
            "SELECT
                id,
                name
             FROM categories
             ORDER BY name ASC"
        );

        $categories =
            $statement->fetchAll();


        return $this->view(
            'organizer/hackathons/edit',
            [
                'organizer' =>
                    $organizer,

                'hackathon' =>
                    $hackathon,

                'categories' =>
                    $categories,

                'editDeadline' =>
                    $editDeadline,

                'csrfToken' =>
                    Csrf::token(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Hackathon
    |--------------------------------------------------------------------------
    */

    public function update(int $id): void
    {
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {

            http_response_code(419);

            exit('Invalid CSRF token.');

        }


        $organizerModel =
            new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );

        if (!$organizer) {

            http_response_code(403);

            exit('Organizer profile not found.');

        }


        $hackathonModel =
            new Hackathon();

        $hackathon =
            $hackathonModel->findForOrganizer(
                $id,
                $organizer['id']
            );


        if (!$hackathon) {

            http_response_code(404);

            exit('Hackathon not found.');

        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Server-side protection.
        |
        | A user cannot bypass the dashboard and directly POST
        | to the update URL after submitting for approval.
        |--------------------------------------------------------------------------
        */

        if (
            ($hackathon['status'] ?? '')
            !== 'draft'
        ) {

            http_response_code(403);

            exit(
                'This hackathon can no longer be edited because it has already been submitted for approval.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | 24-Hour Editing Window
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $hackathon['created_at']
            )
        ) {

            http_response_code(403);

            exit(
                'This hackathon cannot be edited.'
            );

        }


        try {

            $createdAt =
                new \DateTimeImmutable(
                    $hackathon['created_at']
                );

        } catch (\Exception $exception) {

            http_response_code(500);

            exit(
                'Invalid hackathon creation date.'
            );

        }


        $editDeadline =
            $createdAt->modify(
                '+24 hours'
            );

        $now =
            new \DateTimeImmutable();


        if ($now > $editDeadline) {

            http_response_code(403);

            exit(
                'The 24-hour editing period for this hackathon has expired.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Read Form Data
        |--------------------------------------------------------------------------
        */

        $categoryId =
            $this->nullableInteger(
                $_POST['category_id'] ?? null
            );

        $title =
            trim(
                $_POST['title'] ?? ''
            );

        $description =
            trim(
                $_POST['description'] ?? ''
            );

        $rules =
            trim(
                $_POST['rules'] ?? ''
            );

        $requirements =
            trim(
                $_POST['requirements'] ?? ''
            );

        $participationType =
            trim(
                $_POST['participation_type']
                ?? 'individual'
            );

        $minTeamSize =
            $this->nullableInteger(
                $_POST['min_team_size'] ?? null
            );

        $maxTeamSize =
            $this->nullableInteger(
                $_POST['max_team_size'] ?? null
            );

        $maxTeams =
            $this->nullableInteger(
                $_POST['max_teams'] ?? null
            );

        $maxParticipants =
            $this->nullableInteger(
                $_POST['max_participants'] ?? null
            );

        $registrationStart =
            $this->normalizeDate(
                $_POST['registration_start'] ?? null
            );

        $registrationEnd =
            $this->normalizeDate(
                $_POST['registration_end'] ?? null
            );

        $hackathonStart =
            $this->normalizeDate(
                $_POST['hackathon_start'] ?? null
            );

        $hackathonEnd =
            $this->normalizeDate(
                $_POST['hackathon_end'] ?? null
            );

        $submissionDeadline =
            $this->normalizeDate(
                $_POST['submission_deadline'] ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if ($title === '') {

            exit(
                $this->editError(
                    'Hackathon title is required.'
                )
            );

        }


        if ($description === '') {

            exit(
                $this->editError(
                    'Hackathon description is required.'
                )
            );

        }


        if ($categoryId === null) {

            exit(
                $this->editError(
                    'Please select a category.'
                )
            );

        }


        $allowedParticipationTypes = [
            'individual',
            'team',
            'both',
        ];

        if (
            !in_array(
                $participationType,
                $allowedParticipationTypes,
                true
            )
        ) {

            exit(
                $this->editError(
                    'Invalid participation type.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Team Validation
        |--------------------------------------------------------------------------
        */

        if (
            $participationType === 'team'
            || $participationType === 'both'
        ) {

            if ($minTeamSize === null) {

                exit(
                    $this->editError(
                        'Minimum team size is required for team participation.'
                    )
                );

            }


            if ($maxTeamSize === null) {

                exit(
                    $this->editError(
                        'Maximum team size is required for team participation.'
                    )
                );

            }


            if ($minTeamSize < 1) {

                exit(
                    $this->editError(
                        'Minimum team size must be at least 1.'
                    )
                );

            }


            if ($maxTeamSize < $minTeamSize) {

                exit(
                    $this->editError(
                        'Maximum team size cannot be smaller than minimum team size.'
                    )
                );

            }

        } else {

            $minTeamSize = null;
            $maxTeamSize = null;
            $maxTeams = null;

        }


        /*
        |--------------------------------------------------------------------------
        | Team Capacity
        |--------------------------------------------------------------------------
        */

        if (
            (
                $participationType === 'team'
                || $participationType === 'both'
            )
            && $maxTeams !== null
            && $maxTeams < 1
        ) {

            exit(
                $this->editError(
                    'Maximum teams must be at least 1.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Participant Capacity
        |--------------------------------------------------------------------------
        */

        if (
            $maxParticipants !== null
            && $maxParticipants < 1
        ) {

            exit(
                $this->editError(
                    'Maximum participants must be at least 1.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Date Validation
        |--------------------------------------------------------------------------
        */

        if (
            !$registrationStart
            || !$registrationEnd
            || !$hackathonStart
            || !$hackathonEnd
            || !$submissionDeadline
        ) {

            exit(
                $this->editError(
                    'All hackathon dates are required.'
                )
            );

        }


        if (
            strtotime($registrationEnd)
            < strtotime($registrationStart)
        ) {

            exit(
                $this->editError(
                    'Registration end must be after registration start.'
                )
            );

        }


        if (
            strtotime($hackathonStart)
            < strtotime($registrationEnd)
        ) {

            exit(
                $this->editError(
                    'Hackathon start must be on or after registration end.'
                )
            );

        }


        if (
            strtotime($hackathonEnd)
            < strtotime($hackathonStart)
        ) {

            exit(
                $this->editError(
                    'Hackathon end must be after hackathon start.'
                )
            );

        }


        if (
            strtotime($submissionDeadline)
            < strtotime($hackathonEnd)
        ) {

            exit(
                $this->editError(
                    'Submission deadline must be on or after hackathon end.'
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $updated =
            $hackathonModel->updateForOrganizer(
                $id,
                $organizer['id'],
                [
                    'category_id' =>
                        $categoryId,

                    'title' =>
                        $title,

                    'description' =>
                        $description,

                    'rules' =>
                        $rules !== ''
                            ? $rules
                            : null,

                    'requirements' =>
                        $requirements !== ''
                            ? $requirements
                            : null,

                    'participation_type' =>
                        $participationType,

                    'min_team_size' =>
                        $minTeamSize,

                    'max_team_size' =>
                        $maxTeamSize,

                    'max_teams' =>
                        $maxTeams,

                    'max_participants' =>
                        $maxParticipants,

                    'registration_start' =>
                        $registrationStart,

                    'registration_end' =>
                        $registrationEnd,

                    'hackathon_start' =>
                        $hackathonStart,

                    'hackathon_end' =>
                        $hackathonEnd,

                    'submission_deadline' =>
                        $submissionDeadline,
                ]
            );


        Session::flash(
            'success',
            'Hackathon updated successfully.'
        );


        $response =
            new Response();

        $response->redirect(
            '/TECHATHON/public/organizer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit For Approval
    |--------------------------------------------------------------------------
    */

    public function submitForApproval(int $id): void
    {
        if (
            !Csrf::validate(
                $_POST['_csrf_token'] ?? null
            )
        ) {

            http_response_code(419);

            exit('Invalid CSRF token.');

        }


        $organizerModel =
            new Organizer();

        $organizer =
            $organizerModel->findByUserId(
                Auth::id()
            );

        if (!$organizer) {

            http_response_code(403);

            exit(
                'Organizer profile not found.'
            );

        }


        $hackathonModel =
            new Hackathon();

        $hackathon =
            $hackathonModel->findForOrganizer(
                $id,
                $organizer['id']
            );


        if (!$hackathon) {

            http_response_code(404);

            exit(
                'Hackathon not found.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Only Draft Hackathons Can Be Submitted
        |--------------------------------------------------------------------------
        */

        if (
            ($hackathon['status'] ?? '')
            !== 'draft'
        ) {

            http_response_code(403);

            exit(
                'Only draft hackathons can be submitted for approval.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Submit For Admin Approval
        |--------------------------------------------------------------------------
        */

        $hackathonModel->updateStatus(
            $id,
            'pending_approval'
        );


        Session::flash(
            'success',
            'Hackathon submitted for admin approval. It can no longer be edited.'
        );


        $response =
            new Response();

        $response->redirect(
            '/TECHATHON/public/organizer'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Nullable Integer
    |--------------------------------------------------------------------------
    */

    private function nullableInteger(
        mixed $value
    ): ?int {

        if (
            $value === null
            || $value === ''
        ) {

            return null;

        }


        if (
            !is_numeric($value)
        ) {

            return null;

        }


        return (int) $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Normalize Date
    |--------------------------------------------------------------------------
    */

    private function normalizeDate(
        mixed $value
    ): ?string {

        if (
            $value === null
            || trim((string) $value) === ''
        ) {

            return null;

        }


        $value =
            trim(
                (string) $value
            );


        $timestamp =
            strtotime($value);


        if ($timestamp === false) {

            return null;

        }


        return date(
            'Y-m-d H:i:s',
            $timestamp
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Generate Unique Slug
    |--------------------------------------------------------------------------
    */

    private function generateUniqueSlug(
        string $title
    ): string {

        $baseSlug =
            strtolower(
                trim(
                    preg_replace(
                        '/[^a-zA-Z0-9]+/',
                        '-',
                        $title
                    ),
                    '-'
                )
            );


        if ($baseSlug === '') {

            $baseSlug = 'hackathon';

        }


        $db =
            Database::connect();

        $slug =
            $baseSlug;

        $counter =
            1;


        while (true) {

            $statement =
                $db->prepare(
                    "SELECT COUNT(*)
                     FROM hackathons
                     WHERE slug = :slug"
                );

            $statement->execute([
                'slug' =>
                    $slug,
            ]);


            $exists =
                (int) $statement->fetchColumn();


            if ($exists === 0) {

                return $slug;

            }


            $counter++;

            $slug =
                $baseSlug
                . '-'
                . $counter;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Create Error
    |--------------------------------------------------------------------------
    */

    private function createError(
        string $message
    ): string {

        return
            '<div style="font-family: Arial, sans-serif; padding: 40px;">'
            . '<h2>Unable to create hackathon</h2>'
            . '<p>'
            . htmlspecialchars($message)
            . '</p>'
            . '<p>'
            . '<a href="/TECHATHON/public/organizer/hackathons/create">'
            . 'Go back'
            . '</a>'
            . '</p>'
            . '</div>';
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Edit Error
    |--------------------------------------------------------------------------
    */

    private function editError(
        string $message
    ): string {

        return
            '<div style="font-family: Arial, sans-serif; padding: 40px;">'
            . '<h2>Unable to update hackathon</h2>'
            . '<p>'
            . htmlspecialchars($message)
            . '</p>'
            . '<p>'
            . '<a href="/TECHATHON/public/organizer">'
            . 'Back to Organizer Dashboard'
            . '</a>'
            . '</p>'
            . '</div>';
    }
}