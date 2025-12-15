<?php

namespace App\Controllers;

use App\Models\AttendancePermissions;
use App\Models\Permissions;
use Core\Controller;

class AttendancePermissionsController extends Controller
{
    protected $model;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->model = new AttendancePermissions();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/attendance-permissions');

        $data = [
            'title' => 'Attendance Permissions',
            'access' => $access
        ];

        view_with_layout('admin/attendance-permissions/index', $data);
    }

    public function data()
    {
        try {
            $rows = $this->model->getAllWithMahasiswa();

            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'mahasiswa_name' => $item->mahasiswa_name ?? '-',
                    'permission_type' => $item->permission_type ?? '-',
                    'start_date' => $item->start_date ? date('d M Y', strtotime($item->start_date)) : '-',
                    'end_date' => $item->end_date ? date('d M Y', strtotime($item->end_date)) : '-',
                    'status' => $item->status ?? 'pending',
                ];
            }, $rows);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $detail = $this->model->getDetail($id);

            if (!$detail) {
                redirect(base_url('admin/attendance-permissions'));
                exit;
            }

            $data = [
                'title' => 'Attendance Permission Detail',
                'permission' => $detail
            ];

            view_with_layout('admin/attendance-permissions/view', $data);
        } catch (\Exception $e) {
            redirect(base_url('admin/attendance-permissions'));
            exit;
        }
    }

    public function approve($id)
    {
        try {
            $detail = $this->model->getDetail($id);
            if (!$detail) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            if (($detail->status ?? 'pending') !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Status sudah diproses'], 422);
            }

            $adminId = session('user')->id ?? null;
            if (!$adminId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $this->model->approve($id, $adminId);

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil di-approve'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $detail = $this->model->getDetail($id);
            if (!$detail) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            if (($detail->status ?? 'pending') !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Status sudah diproses'], 422);
            }

            $adminId = session('user')->id ?? null;
            if (!$adminId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $note = $_POST['rejection_note'] ?? null;
            if (!$note || trim($note) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Rejection note wajib diisi'
                ], 422);
            }

            $this->model->reject($id, $adminId, $note);

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil di-reject'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
