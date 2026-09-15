<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->view('home/index', [
            'title' => 'TECHATHON',
        ]);
    }
}