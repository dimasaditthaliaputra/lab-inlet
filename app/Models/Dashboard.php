<?php

namespace App\Models;

use Core\Model;

class Dashboard extends Model
{
    public function getSummaryStats()
    {
        // Menggunakan fetch() karena view ini hanya mengembalikan 1 baris rangkuman
        return $this->db->query("SELECT * FROM v_dashboard_summary_cards")->fetch();
    }

    public function getAttendanceToday()
    {
        return $this->db->query("SELECT * FROM v_dashboard_attendance_today")->fetch();
    }

    public function getLatestLogs()
    {
        // Menggunakan fetchAll() karena mengembalikan banyak baris
        return $this->db->query("SELECT * FROM v_dashboard_latest_logs")->fetchAll();
    }

    public function getActivityTrend()
    {
        return $this->db->query("SELECT * FROM mv_dashboard_activity_trend")->fetchAll();
    }

    public function getUserDistribution()
    {
        return $this->db->query("SELECT * FROM mv_dashboard_user_distribution")->fetchAll();
    }

    public function getProjectStats()
    {
        return $this->db->query("SELECT * FROM mv_dashboard_project_stats")->fetchAll();
    }

    public function getStudentGrowth()
    {
        return $this->db->query("SELECT * FROM mv_dashboard_student_year")->fetchAll();
    }

    public function refreshAnalyticsData()
    {
        try {
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_user_distribution");
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_activity_trend");
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_project_stats");
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_student_year");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllDashboardData()
    {
        return [
            'summary'     => $this->getSummaryStats(),
            'attendance'  => $this->getAttendanceToday(),
            'logs'        => $this->getLatestLogs(),
            'charts'      => [
                'activity' => $this->getActivityTrend(),
                'roles'    => $this->getUserDistribution(),
                'projects' => $this->getProjectStats(),
                'students' => $this->getStudentGrowth()
            ]
        ];
    }
}