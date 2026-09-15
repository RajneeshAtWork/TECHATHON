<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\AdminDashboard;

class AdminController extends Controller
{
    public function index(): string
    {
        $dashboardModel = new AdminDashboard();

        return $this->view('admin/index', [
            'title' => 'Admin Dashboard',
            'user' => Auth::user(),
            'statistics' => $dashboardModel->getStatistics(),
        ]);
    }
}