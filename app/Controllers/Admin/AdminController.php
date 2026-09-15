<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class AdminController extends Controller
{
    public function index(): string
    {
        return $this->view('admin/index', [
            'title' => 'Admin Dashboard',
        ]);
    }
}