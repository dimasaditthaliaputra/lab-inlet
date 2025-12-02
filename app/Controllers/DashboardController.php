<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    protected $dashboardModel;

    public function __construct()
    {
        
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        $dashboardData = $this->dashboardModel->getAllDashboardData();

        $data = [
            'title' => 'Dashboard Overview',
            'stats' => $dashboardData
        ];

        view_with_layout('admin/dashboard/index', $data);
    }
}