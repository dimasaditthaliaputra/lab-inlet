<?php

namespace App\Controllers;

use App\Models\AttendanceLog;
use App\Models\AttendancePermissions;
use App\Models\AttendanceSetting;
use Core\Controller;

class PresenceController extends Controller
{
    protected $logModel;
    protected $permissionModel;
    protected $settingModel;

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
    }

    /**
     * Menampilkan halaman absensi (Presence).
     */
    public function index()
    {
        $user = session('user');
        $mahasiswaId = $user->mahasiswa_id ?? null;

        $settings = $this->settingModel->find(12);
        $checkInTime = $settings->clock_in_start_time ?? '08:00:00';
        $checkOutTime = $settings->clock_in_end_time ?? '17:00:00';

        $currentLog = $this->logModel->getLatestLogByMahasiswaId($mahasiswaId);

        $hasCheckedIn = $this->logModel->hasCheckedInToday($mahasiswaId);
        $hasCheckedOut = $this->logModel->hasCheckedOutToday($mahasiswaId);

        $isPermitted = $this->permissionModel->isPermittedToday($mahasiswaId);

        $data = [
            'title' => 'Presence (Absensi)',
            'user' => $user,
            'checkInTime' => $checkInTime,
            'checkOutTime' => $checkOutTime,
            'currentLog' => $currentLog,
            'isPermitted' => $isPermitted,
            'hasCheckedIn' => $hasCheckedIn,
            'hasCheckedOut' => $hasCheckedOut,
        ];

        view_with_layout_mahasiswa('mahasiswa/presence/index', $data);
    }

    /**
     * Memproses absensi Masuk (Check-in) atau Keluar (Check-out).
     */
    public function process()
    {
        $user = session('user');
        $mahasiswaId = $user->mahasiswa_id ?? null;
        $currentTime = date('H:i:s');

        $validation = validate([
            'log_type' => ['required' => true, 'in' => ['check_in', 'check_out']],
            'latitude' => ['required' => true],
            'longitude' => ['required' => true],
            'photo_path' => ['required' => true],
        ]);

        if (!$validation['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap atau lokasi tidak valid.',
                'errors' => $validation['errors']
            ], 422);
        }

        $logType = $validation['data']['log_type'];
        $latitude = $validation['data']['latitude'];
        $longitude = $validation['data']['longitude'];
        $photoDataURL = $validation['data']['photo_path'];

        $settings = $this->settingModel->find(12);

        $startCheckIn = strtotime($settings->clock_in_start_time ?? '08:00:00');
        $limitCheckIn = strtotime($settings->clock_in_end_time ?? '09:00:00');
        $startCheckOut = strtotime($settings->clock_out_start_time ?? '17:00:00');

        $now = strtotime($currentTime);
        $startWindow = $startCheckIn - (60 * 60);

        if ($this->permissionModel->isPermittedToday($mahasiswaId)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sedang dalam masa izin/cuti yang disetujui hari ini.'
            ], 403);
        }

        if ($now < $startWindow) {
            $readableStartTime = date('H:i', $startWindow);
            return response()->json([
                'success' => false,
                'message' => "Absensi baru dapat dilakukan pada pukul $readableStartTime."
            ], 403);
        }

        if ($logType == 'check_in') {
            if ($now > $limitCheckIn) {
                $readableEndTime = date('H:i', $limitCheckIn);
                return response()->json([
                    'success' => false,
                    'message' => "Waktu Check-in sudah berakhir pada pukul $readableEndTime."
                ], 403);
            }

            if ($this->logModel->hasCheckedInToday($mahasiswaId)) {
                return response()->json(['success' => false, 'message' => 'Anda sudah Check-in hari ini.'], 400);
            }

            if ($now < $startCheckIn) {
                $status = 'Early Check-in';
            } elseif ($now > $startCheckIn) {
                $status = 'Late Check-in';
            } else {
                $status = 'On Time';
            }
            $statusLog = $status;
        } elseif ($logType == 'check_out') {
            if (!$this->logModel->hasCheckedInToday($mahasiswaId) || $this->logModel->hasCheckedOutToday($mahasiswaId)) {
                return response()->json(['success' => false, 'message' => 'Anda belum Check-in atau sudah Check-out hari ini.'], 400);
            }

            if ($now < $limitCheckIn) {
                return response()->json([
                    'success' => false,
                    'message' => "Belum bisa melakukan Check-out saat ini."
                ], 403);
            } elseif ($now >= $limitCheckIn && $now < $startCheckOut) {
                $status = 'Left Early';
            } else {
                $status = 'On Time Check-out';
            }

            $statusLog = $status;
        }

        list($type, $photoData) = explode(';', $photoDataURL);
        list(, $photoData)      = explode(',', $photoData);
        $photoData = base64_decode($photoData);

        $fileName = 'presence_' . $mahasiswaId . '_' . time() . '.jpeg';
        $uploadPath = 'uploads/attendance/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fullPath = $uploadPath . $fileName;

        if (file_put_contents($fullPath, $photoData) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan foto absensi.'], 500);
        }

        $data = [
            'mahasiswa_id' => $mahasiswaId,
            'log_type' => $logType,
            'status' => $statusLog,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'photo_path' => $fullPath,
        ];

        $this->logModel->create($data);

        $message = ($logType == 'check_in') ? "Check-in berhasil! Status: $status" : "Check-out berhasil ($status).";

        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
