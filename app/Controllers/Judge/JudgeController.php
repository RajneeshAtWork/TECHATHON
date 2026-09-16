<?php

namespace App\Controllers\Judge;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Judge;

class JudgeController extends Controller
{
    public function index(): string
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
            exit(
                '403 - Judge profile not found.'
            );
        }

        $assignedHackathons =
            $judgeModel->getAssignedHackathons(
                (int) $judge['id']
            );

        $pendingEvaluations =
            $judgeModel->countPendingEvaluations(
                (int) $judge['id']
            );

        $completedEvaluations =
            $judgeModel->countCompletedEvaluations(
                (int) $judge['id']
            );

        return $this->view(
            'judge/dashboard',
            [
                'title' => 'Judge Dashboard',

                'judge' => $judge,

                'assignedHackathons' =>
                    $assignedHackathons,

                'hackathonCount' =>
                    count($assignedHackathons),

                'pendingEvaluations' =>
                    $pendingEvaluations,

                'completedEvaluations' =>
                    $completedEvaluations,
            ]
        );
    }
}