<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home Page',
        ];

        view_with_layout_homepage('home/index', $data);
    }
}