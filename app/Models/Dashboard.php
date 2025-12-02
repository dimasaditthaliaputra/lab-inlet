<?php

namespace App\Models;

use Core\Model;

class Dashboard extends Model
{
    public function getSummaryStats()
    {
        return $this->db->query("SELECT * FROM v_dashboard_summary_cards")->fetch();
    }

    public function getAttendanceToday()
    {
        return $this->db->query("SELECT * FROM v_dashboard_attendance_today")->fetch();
    }

    public function getActivityTrend($range = 'week')
    {
        $interval = ($range == 'month') ? "30 days" : "7 days";

        $sql = "SELECT 
                    log_date, 
                    total_activity,
                    CASE 
                        WHEN :range = 'month' THEN date_label 
                        ELSE day_name 
                    END as label
                FROM mv_dashboard_activity_trend 
                WHERE log_date >= CURRENT_DATE - INTERVAL '$interval'
                ORDER BY log_date ASC";

        return $this->db->query($sql)
            ->bind(':range', $range)
            ->fetchAll();
    }

    public function getLatestLogs()
    {
        return $this->db->query("SELECT * FROM v_dashboard_latest_logs")->fetchAll();
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
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_user_distribution")->execute();
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_activity_trend")->execute();
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_project_stats")->execute();
            $this->db->query("REFRESH MATERIALIZED VIEW mv_dashboard_student_year")->execute();
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
