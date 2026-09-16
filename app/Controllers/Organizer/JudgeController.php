<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Hackathon;
use App\Models\Organizer;
use App\Models\OrganizerJudge;

class JudgeController extends Controller
{
    /**
     * Display assigned judges and available judges.
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
        $judgeModel = new OrganizerJudge();

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

        $assignedJudges = $judgeModel->getForHackathon(
            $hackathonId,
            (int) $organizer['id']
        );

        $availableJudges = $judgeModel->getAvailableJudges(
            $hackathonId,
            (int) $organizer['id']
        );

        return $this->view(
            'organizer/hackathons/judges',
            [
                'title' => 'Judges - ' . $hackathon['title'],
                'hackathon' => $hackathon,
                'assignedJudges' => $assignedJudges,
                'availableJudges' => $availableJudges,
                'csrfToken' => Csrf::token(),
                'successMessage' => Session::getFlash('success'),
                'errorMessage' => Session::getFlash('error'),
            ]
        );
    }


    /**
     * Assign a judge to a hackathon.
     */
    public function assign(int $hackathonId): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            header('Location: /TECHATHON/public/login');
            exit;
        }

        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Session::flash(
                'error',
                'Invalid security token. Please try again.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        $judgeId = filter_var(
            $_POST['judge_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$judgeId || $judgeId < 1) {
            Session::flash(
                'error',
                'Please select a valid judge.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        $organizerModel = new Organizer();
        $hackathonModel = new Hackathon();
        $judgeModel = new OrganizerJudge();

        $organizer = $organizerModel->findByUserId($userId);

        if (!$organizer) {
            http_response_code(403);
            return;
        }

        $hackathon = $hackathonModel->findForOrganizer(
            $hackathonId,
            (int) $organizer['id']
        );

        if (!$hackathon) {
            http_response_code(404);
            return;
        }

        $judge = $judgeModel->findJudge($judgeId);

        if (!$judge) {
            Session::flash(
                'error',
                'Judge profile not found.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        if (($judge['status'] ?? 'active') !== 'active') {
            Session::flash(
                'error',
                'This judge account is not active.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        if ($judgeModel->isAssigned($hackathonId, $judgeId)) {
            Session::flash(
                'error',
                'This judge is already assigned to the hackathon.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        if (!$judgeModel->assign($hackathonId, $judgeId)) {
            Session::flash(
                'error',
                'Unable to assign the judge.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        Session::flash(
            'success',
            'Judge assigned successfully.'
        );

        header(
            'Location: /TECHATHON/public/organizer/hackathons/'
            . $hackathonId
            . '/judges'
        );

        exit;
    }


    /**
     * Remove a judge assignment.
     */
    public function remove(
        int $hackathonId,
        int $assignmentId
    ): void {
        $userId = Auth::id();

        if ($userId === null) {
            header('Location: /TECHATHON/public/login');
            exit;
        }

        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Session::flash(
                'error',
                'Invalid security token. Please try again.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        $organizerModel = new Organizer();
        $hackathonModel = new Hackathon();
        $judgeModel = new OrganizerJudge();

        $organizer = $organizerModel->findByUserId($userId);

        if (!$organizer) {
            http_response_code(403);
            return;
        }

        $hackathon = $hackathonModel->findForOrganizer(
            $hackathonId,
            (int) $organizer['id']
        );

        if (!$hackathon) {
            http_response_code(404);
            return;
        }

        $assignment = $judgeModel->findAssignmentForOrganizer(
            $assignmentId,
            $hackathonId,
            (int) $organizer['id']
        );

        if (!$assignment) {
            Session::flash(
                'error',
                'Judge assignment not found.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        /*
         * Preserve existing evaluation history.
         *
         * A judge who has already evaluated submissions should
         * not have the assignment deleted from the database.
         */
        $evaluationCount = (int) (
            $assignment['evaluation_count'] ?? 0
        );

        /*
         * The assignment query currently does not return
         * evaluation_count, so verify directly when necessary.
         */
        $evaluationCount = $this->countJudgeEvaluations(
            (int) $assignment['judge_id'],
            $hackathonId
        );

        if ($evaluationCount > 0) {
            Session::flash(
                'error',
                'This judge already has evaluations for this hackathon and cannot be removed.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        if (!$judgeModel->removeAssignment(
            $assignmentId,
            $hackathonId
        )) {
            Session::flash(
                'error',
                'Unable to remove the judge assignment.'
            );

            header(
                'Location: /TECHATHON/public/organizer/hackathons/'
                . $hackathonId
                . '/judges'
            );

            exit;
        }

        Session::flash(
            'success',
            'Judge removed successfully.'
        );

        header(
            'Location: /TECHATHON/public/organizer/hackathons/'
            . $hackathonId
            . '/judges'
        );

        exit;
    }


    /**
     * Count evaluations submitted by a judge for a hackathon.
     */
    private function countJudgeEvaluations(
        int $judgeId,
        int $hackathonId
    ): int {
        $judgeModel = new OrganizerJudge();

        $sql = "
            SELECT COUNT(*)

            FROM evaluations e

            INNER JOIN submissions s
                ON s.id = e.submission_id

            INNER JOIN projects p
                ON p.id = s.project_id

            WHERE e.judge_id = :judge_id
              AND p.hackathon_id = :hackathon_id
        ";

        $statement = $judgeModel->getDatabase()->prepare($sql);

        $statement->execute([
            'judge_id' => $judgeId,
            'hackathon_id' => $hackathonId,
        ]);

        return (int) $statement->fetchColumn();
    }
}