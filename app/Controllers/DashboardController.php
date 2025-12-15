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
            redirect(base_url('login'));
            exit;
        }

        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        $this->dashboardModel->refreshAnalyticsData();

        $dashboardData = $this->dashboardModel->getAllDashboardData();

        $data = [
            'title' => 'Dashboard Overview',
            'stats' => $dashboardData
        ];

        view_with_layout('admin/dashboard/index', $data);
    }

    public function activity_data()
    {
        $filter = $_GET['filter'] ?? 'week';

        try {
            $data = $this->dashboardModel->getActivityTrend($filter);

            $formattedData = array_map(function ($item) {
                return [
                    'x' => $item->label,
                    'y' => (int)$item->total_activity
                ];
            }, $data);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $formattedData
            ]);
            exit;
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}
