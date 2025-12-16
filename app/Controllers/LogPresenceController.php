<?php

namespace App\Controllers;

use App\Models\Mahasiswa;
use Core\Controller;
use Core\Database;

class LogPresenceController extends Controller
{
    protected $db;
    protected $mahasiswaModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->db = new Database();
        $this->mahasiswaModel = new Mahasiswa();
    }

    public function index()
    {
        $data = [
            'title' => 'Presence History'
        ];

        view_with_layout_mahasiswa('mahasiswa/history/index', $data);
    }

    public function data()
    {
        try {
            $user = session('user');
            $year = request('year') ?? date('Y');
            $month = request('month') ?? date('m');

            $attendanceData = $this->db->query("SELECT * FROM get_daily_attendance_summary(:id, :year, :month)")
                ->bind(':id', $user->mahasiswa_id)
                ->bind(':year', $year)
                ->bind(':month', $month)
                ->fetchAll();

            $startDate = "$year-$month-01";
            $endDate = date("Y-m-t", strtotime($startDate));

            $permissionsData = $this->db->query("
                SELECT * FROM attendance_permissions 
                WHERE mahasiswa_id = :id 
                AND status = 'approved'
                AND (
                    (start_date <= :end_date AND end_date >= :start_date)
                )
            ")
            ->bind(':id', $user->mahasiswa_id)
            ->bind(':start_date', $startDate)
            ->bind(':end_date', $endDate)
            ->fetchAll();

            $mappedData = [];

            foreach ($attendanceData as $row) {
                $mappedData[$row->attendance_date] = [
                    'type' => 'present',
                    'check_in' => $row->check_in_time ? substr($row->check_in_time, 0, 5) : null,
                    'check_out' => $row->check_out_time ? substr($row->check_out_time, 0, 5) : null,
                    'status_in' => $row->check_in_status,
                    'status_out' => $row->check_out_status,
                    'photo_in' =>  $row->check_in_photo_path,
                    'photo_out' => $row->check_out_photo_path
                ];
            }

            foreach ($permissionsData as $perm) {
                $current = strtotime($perm->start_date);
                $end = strtotime($perm->end_date);

                while ($current <= $end) {
                    $dateStr = date('Y-m-d', $current);
                    
                    if (date('m', $current) == $month && date('Y', $current) == $year) {
                        $mappedData[$dateStr] = [
                            'type' => 'permission',
                            'title' => $perm->permission_type,
                            'reason' => $perm->reason,
                            'status' => $perm->status
                        ];
                    }
                    $current = strtotime("+1 day", $current);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $mappedData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexAdmin()
    {
        $data = [
            'title' => 'All Log Presence'
        ];

        view_with_layout('admin/history/index', $data);
    }

    public function dataAdmin()
    {
        try {
            $year = request('year') ? (int)request('year') : date('Y');
            $month = request('month') ? (int)request('month') : date('n');
            
            // 1. Ambil Semua Mahasiswa
            $students = $this->mahasiswaModel->getDataAll();

            $logs = $this->db->query("SELECT * FROM get_daily_attendance_summary(NULL, :year, :month)")
                ->bind(':year', $year)
                ->bind(':month', $month)
                ->fetchAll();

            // 3. Ambil Izin (Approved) 1 Bulan Penuh
            $startDate = "$year-$month-01";
            $endDate = date("Y-m-t", strtotime($startDate));
            
            $permissions = $this->db->query("
                SELECT * FROM attendance_permissions 
                WHERE status = 'approved'
                AND (
                    (start_date <= :end_date AND end_date >= :start_date)
                )
            ")
            ->bind(':start_date', $startDate)
            ->bind(':end_date', $endDate)
            ->fetchAll();

            // 4. Mapping Data ke format [mahasiswa_id][tanggal]
            $matrixData = [];

            // Mapping Log Hadir
            foreach ($logs as $log) {
                $matrixData[$log->mahasiswa_id][$log->attendance_date] = [
                    'type' => 'present',
                    'in' => $log->check_in_time ? substr($log->check_in_time, 0, 5) : null,
                    'out' => $log->check_out_time ? substr($log->check_out_time, 0, 5) : null,
                    'status_in' => $log->check_in_status,
                    'status_out' => $log->check_out_status,
                    'photo_in' => $log->check_in_photo_path,
                    'photo_out' => $log->check_out_photo_path
                ];
            }

            // Mapping Izin (Override Hadir jika ada, sesuai prioritas)
            foreach ($permissions as $perm) {
                $current = strtotime($perm->start_date);
                $end = strtotime($perm->end_date);

                while ($current <= $end) {
                    // Hanya ambil tanggal yang masuk dalam bulan yang dipilih
                    if (date('n', $current) == $month && date('Y', $current) == $year) {
                        $dateStr = date('Y-m-d', $current);
                        $mId = $perm->mahasiswa_id;

                        $matrixData[$mId][$dateStr] = [
                            'type' => 'permission',
                            'title' => $perm->permission_type,
                            'reason' => $perm->reason
                        ];
                    }
                    $current = strtotime("+1 day", $current);
                }
            }

            // 5. Susun Response Akhir
            return response()->json([
                'success' => true,
                'days_in_month' => (int)date('t', strtotime($startDate)),
                'students' => $students,
                'attendance_matrix' => $matrixData,
                'current_date' => date('Y-m-d') // Untuk hitung Alpha (hanya sampai hari ini)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
