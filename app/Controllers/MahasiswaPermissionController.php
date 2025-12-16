<?php

namespace App\Controllers;

use App\Models\AttendancePermissions;
use Core\Controller;

class MahasiswaPermissionController extends Controller
{
    protected $permissionModel;

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

        $this->permissionModel = new AttendancePermissions();
    }

    public function index()
    {
        $data = [
            'title' => 'Request Permissions',
        ];

        view_with_layout_mahasiswa('mahasiswa/permission/index', $data);
    }

    public function data()
    {
        try {
            $user = session('user');
            $data = $this->permissionModel->getByMahasiswaId($user->mahasiswa_id);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store()
    {
        $user = session('user');
        $mahasiswaId = $user->mahasiswa_id;

        $validation = validate([
            'permission_type' => ['required' => true],
            'start_date' => ['required' => true],
            'end_date' => ['required' => true],
            'reason' => ['required' => true]
        ]);

        if (!$validation['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validation['errors']
            ], 422);
        }

        $post = $validation['data'];

        if (strtotime($post['end_date']) < strtotime($post['start_date'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.'
            ], 422);
        }

        $attachmentPath = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['attachment'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

            if (!in_array(strtolower($ext), $allowed)) {
                return response()->json(['success' => false, 'message' => 'Format file harus JPG, PNG, atau PDF.'], 422);
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 2MB.'], 422);
            }

            $fileName = 'permit_' . $mahasiswaId . '_' . time() . '.' . $ext;
            $uploadDir = 'uploads/attendance_permissions/';

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                $attachmentPath = $fileName;
            }
        }

        $data = [
            'mahasiswa_id' => $mahasiswaId,
            'permission_type' => $post['permission_type'],
            'start_date' => $post['start_date'],
            'end_date' => $post['end_date'],
            'reason' => $post['reason'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->permissionModel->create($data);
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = $this->permissionModel->find($id);
            $user = session('user');

            if (!$permission || $permission->mahasiswa_id != $user->mahasiswa_id) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
            }

            if ($permission->status != 'pending') {
                return response()->json(['success' => false, 'message' => 'Hanya status Pending yang dapat dihapus.'], 403);
            }

            if ($permission->attachment && file_exists($permission->attachment)) {
                unlink($permission->attachment);
            }

            $this->permissionModel->delete($id);

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server error.'], 500);
        }
    }
}
