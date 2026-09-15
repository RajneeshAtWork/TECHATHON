<?php

namespace App\Controllers;

class HomeController
{
    public function index(): string
    {
        return '
            <h1>TECHATHON</h1>
            <p>Home Controller is working ✅</p>
        ';
    }
}