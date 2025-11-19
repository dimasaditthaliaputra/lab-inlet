<?php

namespace App\Controllers;

use Core\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard'
        ];

        view_with_layout('admin/dashboard/index', $data);
    }
}