<?php

namespace App\Controllers\Organizer;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Organizer;

class OrganizerController extends Controller
{
    public function index(): string
    {
        $userId = Auth::id();

        if ($userId === null) {
            http_response_code(401);

            return '401 - Unauthorized';
        }

        $organizerModel = new Organizer();

        $organizer = $organizerModel->findByUserId($userId);

        if (!$organizer) {
            http_response_code(404);

            return $this->view('organizer/not-found', [
                'title' => 'Organizer Profile Not Found',
            ]);
        }

        return $this->view('organizer/index', [
            'title' => 'Organizer Dashboard',
            'user' => Auth::user(),
            'organizer' => $organizer,
            'statistics' =>
                $organizerModel->getDashboardStatistics(
                    (int) $organizer['id']
                ),
            'hackathons' =>
                $organizerModel->getHackathons(
                    (int) $organizer['id']
                ),
        ]);
    }
}