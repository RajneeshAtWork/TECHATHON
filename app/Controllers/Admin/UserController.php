<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(): string
    {
        $userModel = new User();

        return $this->view('admin/users/index', [
            'title' => 'User Management',
            'users' => $userModel->getAllWithRoles(),
        ]);
    }
}
