<?php

namespace App\Controllers\Judge;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Evaluation;
use App\Models\Judge;

class SubmissionController extends Controller
{
    /**
     * Show submissions for an assigned hackathon.
     */
    public function index(int $hackathonId): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $judgeModel = new Judge();

        $judge =
            $judgeModel->findByUserId($userId);

        if (!$judge) {
            http_response_code(403);
            exit('403 - Judge profile not found.');
        }

        if (
            !$judgeModel->isAssignedToHackathon(
                (int) $judge['id'],
                $hackathonId
            )
        ) {
            http_response_code(403);
            exit(
                '403 - You are not assigned to this hackathon.'
            );
        }

        $submissions =
            $judgeModel->getSubmissionsForHackathon(
                (int) $judge['id'],
                $hackathonId
            );

        return $this->view(
            'judge/submissions/index',
            [
                'title' => 'Hackathon Submissions',
                'submissions' => $submissions,
                'hackathonId' => $hackathonId,
            ]
        );
    }


    /**
     * Show evaluation form for one submission.
     */
    public function evaluate(int $submissionId): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);
            exit('401 - Unauthorized');
        }

        $judgeModel = new Judge();

        $judge =
            $judgeModel->findByUserId($userId);

        if (!$judge) {
            http_response_code(403);
            exit('403 - Judge profile not found.');
        }

        $submission =
            $judgeModel->getSubmissionForJudge(
                (int) $judge['id'],
                $submissionId
            );

        if (!$submission) {
            http_response_code(403);
            exit(
                '403 - This submission is not assigned to you.'
            );
        }

        $evaluationModel =
            new Evaluation();

        $criteria =
            $evaluationModel->getCriteriaForHackathon(
                (int) $submission['hackathon_id']
            );

        $existingEvaluations =
            $evaluationModel->getForSubmissionByJudge(
                $submissionId,
                (int) $judge['id']
            );

        return $this->view(
            'judge/submissions/evaluate',
            [
                'title' => 'Evaluate Submission',
                'submission' => $submission,
                'criteria' => $criteria,
                'existingEvaluations' =>
                    $existingEvaluations,
                'csrfToken' => Csrf::token(),
            ]
        );
    }


    /**
     * Save evaluations.
     */
    public function store(int $submissionId): void
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

        $judgeModel = new Judge();

        $judge =
            $judgeModel->findByUserId($userId);

        if (!$judge) {
            http_response_code(403);
            exit('403 - Judge profile not found.');
        }

        $submission =
            $judgeModel->getSubmissionForJudge(
                (int) $judge['id'],
                $submissionId
            );

        if (!$submission) {
            http_response_code(403);
            exit(
                '403 - This submission is not assigned to you.'
            );
        }

        $evaluationModel =
            new Evaluation();

        $criteria =
            $evaluationModel->getCriteriaForHackathon(
                (int) $submission['hackathon_id']
            );

        if (empty($criteria)) {
            Session::flash(
                'error',
                'No evaluation criteria have been configured for this hackathon.'
            );

            header(
                'Location: /TECHATHON/public/judge/submissions/' .
                $submissionId .
                '/evaluate'
            );
            exit;
        }

        $scores =
            $_POST['scores'] ?? [];

        $feedback =
            trim(
                (string) (
                    $_POST['feedback'] ?? ''
                )
            );


        foreach ($criteria as $criterion) {

            $criteriaId =
                (int) $criterion['id'];

            if (
                !array_key_exists(
                    $criteriaId,
                    $scores
                )
            ) {
                Session::flash(
                    'error',
                    'Please provide a score for every criterion.'
                );

                header(
                    'Location: /TECHATHON/public/judge/submissions/' .
                    $submissionId .
                    '/evaluate'
                );
                exit;
            }


            if (
                !is_numeric(
                    $scores[$criteriaId]
                )
            ) {
                Session::flash(
                    'error',
                    'Each score must be numeric.'
                );

                header(
                    'Location: /TECHATHON/public/judge/submissions/' .
                    $submissionId .
                    '/evaluate'
                );
                exit;
            }


            $score =
                (float) $scores[$criteriaId];

            $maxScore =
                (float) $criterion['max_score'];


            if (
                $score < 0 ||
                $score > $maxScore
            ) {
                Session::flash(
                    'error',
                    'Scores must be between 0 and the maximum score for each criterion.'
                );

                header(
                    'Location: /TECHATHON/public/judge/submissions/' .
                    $submissionId .
                    '/evaluate'
                );
                exit;
            }


            if (
                $evaluationModel->exists(
                    $submissionId,
                    (int) $judge['id'],
                    $criteriaId
                )
            ) {
                Session::flash(
                    'error',
                    'This submission has already been evaluated by you.'
                );

                header(
                    'Location: /TECHATHON/public/judge/submissions/' .
                    $submissionId .
                    '/evaluate'
                );
                exit;
            }
        }


        foreach ($criteria as $criterion) {

            $criteriaId =
                (int) $criterion['id'];

            $score =
                (float) $scores[$criteriaId];

            $evaluationModel->create(
                $submissionId,
                (int) $judge['id'],
                $criteriaId,
                $score,
                $feedback !== ''
                    ? $feedback
                    : null
            );
        }


        Session::flash(
            'success',
            'Evaluation saved successfully.'
        );

        header(
            'Location: /TECHATHON/public/judge'
        );
        exit;
    }
}