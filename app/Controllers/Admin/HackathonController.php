<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
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
            'csrfToken' => Csrf::token(),
        ]);
    }

    /**
     * Approve a pending hackathon.
     */
    public function approve(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $hackathonModel = new Hackathon();

        $hackathon = $hackathonModel->find($id);

        if (!$hackathon) {
            http_response_code(404);
            exit('404 - Hackathon Not Found');
        }

        if (($hackathon['status'] ?? '') !== 'pending_approval') {
            Session::flash(
                'error',
                'Only hackathons waiting for approval can be approved.'
            );

            header(
                'Location: /TECHATHON/public/admin/hackathons/pending'
            );
            exit;
        }

        $hackathonModel->updateStatus(
            $id,
            'approved'
        );

        Session::flash(
            'success',
            'Hackathon approved successfully.'
        );

        header(
            'Location: /TECHATHON/public/admin/hackathons/pending'
        );
        exit;
    }

    /**
     * Reject a pending hackathon.
     *
     * Rejected hackathons return to draft so the organizer
     * can make corrections and submit again.
     */
    public function reject(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf_token'] ?? null)) {
            http_response_code(419);
            exit('419 - Invalid CSRF token');
        }

        $hackathonModel = new Hackathon();

        $hackathon = $hackathonModel->find($id);

        if (!$hackathon) {
            http_response_code(404);
            exit('404 - Hackathon Not Found');
        }

        if (($hackathon['status'] ?? '') !== 'pending_approval') {
            Session::flash(
                'error',
                'Only hackathons waiting for approval can be rejected.'
            );

            header(
                'Location: /TECHATHON/public/admin/hackathons/pending'
            );
            exit;
        }

        $hackathonModel->updateStatus(
            $id,
            'draft'
        );

        Session::flash(
            'success',
            'Hackathon rejected and returned to draft.'
        );

        header(
            'Location: /TECHATHON/public/admin/hackathons/pending'
        );
        exit;
    }
}