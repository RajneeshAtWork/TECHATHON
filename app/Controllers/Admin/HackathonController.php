<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Hackathon;

class HackathonController extends Controller
{
    /**
     * Display all hackathons.
     */
    public function index(): string
    {
        $hackathonModel = new Hackathon();

        return $this->view('admin/hackathons/index', [
            'title' => 'Hackathon Management',
            'hackathons' => $hackathonModel->getAllWithDetails(),
        ]);
    }

    /**
     * Display hackathons waiting for approval.
     */
    public function pending(): string
    {
        $hackathonModel = new Hackathon();

        return $this->view('admin/hackathons/pending', [
            'title' => 'Pending Hackathons',
            'hackathons' => $hackathonModel->getPendingApproval(),
        ]);
    }
}