<?php

namespace App\Controllers;

use App\Models\AttendanceSetting;
use App\Models\Permissions;
use Core\Controller;

class AttendanceSettingsController extends Controller
{
    protected $attendanceSettingModel;
    protected $permissionsModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->attendanceSettingModel = new AttendanceSetting();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $attendanceSettings = $this->attendanceSettingModel->getAll();

        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/attendance-settings');

        $data = [
            'title' => 'Attendance Setting',
            'data' => $attendanceSettings,
            'access' => $access
        ];

        view_with_layout('admin/attendance_settings/index', $data);
    }

    public function data()
    {
        try {
            $attendanceSettings = $this->attendanceSettingModel->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $attendanceSettings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store()
    {
        try {
            $data = [
                'clock_in_start_time'    => request('clock_in_start_time'),
                'clock_in_end_time'      => request('clock_in_end_time'),
                'clock_out_start_time'   => request('clock_out_start_time'),
            ];

            $this->attendanceSettingModel->create($data);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan Attendance'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $attendanceSettings = $this->attendanceSettingModel->findBy('id', $id);

            if (!$attendanceSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $attendanceSettings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }


    public function update($id)
    {
        try {
            $attendanceSettings = $this->attendanceSettingModel->find($id);

            if (!$attendanceSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data attendance tidak ditemukan.'
                ], 404);
            }

            $form = [
                'clock_in_start_time'    => request('clock_in_start_time'),
                'clock_in_end_time'      => request('clock_in_end_time'),
                'clock_out_start_time'   => request('clock_out_start_time'),
            ];

            $this->attendanceSettingModel->update($id, $form);

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data Attendance',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $attendanceSettings = $this->attendanceSettingModel->find($id);

            if (!$attendanceSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data attendance tidak ditemukan.'
                ], 404);
            }

            $this->attendanceSettingModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data attendance',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
