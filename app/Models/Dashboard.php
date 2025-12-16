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
        $days = ($range == 'month') ? 30 : 7;

        $endDate = date('Y-m-d');

        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        $label_select = ($range == 'month')
            ? "TO_CHAR(ds.date_col, 'DD Mon') AS label"
            : "TO_CHAR(ds.date_col, 'Dy') AS label";

        $sql = "
        WITH DateSeries AS (
            -- Tambahkan + 1 day pada start date agar rentangnya akurat (misal: 7 hari = 7 tanggal)
            SELECT (GENERATE_SERIES(
                DATE '{$startDate}', 
                DATE '{$endDate}', 
                '1 day'::interval
            ))::date AS date_col
        )
        SELECT 
            ds.date_col AS log_date,
            COALESCE(mvat.total_activity, 0) AS total_activity, 
            {$label_select} 
        FROM DateSeries ds
        LEFT JOIN mv_dashboard_activity_trend mvat ON mvat.log_date = ds.date_col
        ORDER BY ds.date_col ASC
    ";

        return $this->db->query($sql)->fetchAll();
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
