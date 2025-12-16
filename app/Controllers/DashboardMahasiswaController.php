<?php

namespace App\Controllers;

use App\Models\AttendanceLog;
use App\Models\AttendancePermissions;
use App\Models\AttendanceSetting;
use Core\Controller;

class DashboardMahasiswaController extends Controller
{
    protected $logModel;
    protected $permissionModel;
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $user = session('user');
        if ($user->id_roles != 3) {
            redirect(base_url('admin/dashboard'));
            exit;
        }

        $this->logModel = new AttendanceLog();
        $this->permissionModel = new AttendancePermissions();
        $this->settingModel = new AttendanceSetting();
        $this->db = new \Core\Database();
    }

    public function index()
    {
        $user = session('user');
        $mahasiswaId = $user->mahasiswa_id;

        // 1. Cek Status Absensi Hari Ini untuk Card Shortcut
        $hasCheckedIn = $this->logModel->hasCheckedInToday($mahasiswaId);
        $hasCheckedOut = $this->logModel->hasCheckedOutToday($mahasiswaId);

        // Tentukan pesan status absensi
        $attendanceStatus = [
            'state' => 'idle', // idle, checked_in, done
            'message' => 'Silakan Check-in',
            'icon' => 'bi-box-arrow-in-right',
            'color' => 'primary'
        ];

        if ($hasCheckedIn && !$hasCheckedOut) {
            $attendanceStatus = [
                'state' => 'checked_in',
                'message' => 'Saatnya Check-out',
                'icon' => 'bi-box-arrow-right',
                'color' => 'warning'
            ];
        } elseif ($hasCheckedIn && $hasCheckedOut) {
            $attendanceStatus = [
                'state' => 'done',
                'message' => 'Absensi Selesai',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success'
            ];
        }

        // 2. Ambil Izin Terakhir (Limit 1)
        $allPermissions = $this->permissionModel->getByMahasiswaId($mahasiswaId);
        $latestPermission = !empty($allPermissions) ? $allPermissions[0] : null;

        // 3. Ambil 5 Aktivitas Terakhir (Logs)
        // Kita query manual limit 5 agar ringan, format disesuaikan dengan kebutuhan view
        $recentLogs = $this->db->query("
            SELECT * FROM attendance_logs 
            WHERE mahasiswa_id = :id 
            ORDER BY log_time DESC 
            LIMIT 5
        ")->bind(':id', $mahasiswaId)->fetchAll();

        // 4. Greeting berdasarkan waktu
        $hour = date('H');
        if ($hour < 12) $greeting = 'Selamat Pagi';
        elseif ($hour < 15) $greeting = 'Selamat Siang';
        elseif ($hour < 18) $greeting = 'Selamat Sore';
        else $greeting = 'Selamat Malam';

        $data = [
            'title' => 'Dashboard Mahasiswa',
            'user' => $user,
            'greeting' => $greeting,
            'attendanceStatus' => $attendanceStatus,
            'latestPermission' => $latestPermission,
            'recentLogs' => $recentLogs
        ];

        view_with_layout_mahasiswa('mahasiswa/dashboard/index', $data);
    }
}
