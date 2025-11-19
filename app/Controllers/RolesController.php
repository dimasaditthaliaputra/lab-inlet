<?php

namespace App\Controllers;

use App\Models\Roles;
use Core\Controller;

use function PHPSTORM_META\map;

class RolesController extends Controller
{
    protected $rolesModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->rolesModel = new Roles();
    }

    public function index()
    {
        $data = [
            'title' => 'Roles User',
        ];

        view_with_layout('admin/roles/index', $data);
    }

    public function data()
    {
        try {
            $roles = $this->rolesModel->orderBy('role_name', 'ASC');

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $roles
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
            $validation = validate([
                'role_name' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Role Name is required'
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $data = [
                'role_name' => $validation['data']['role_name']
            ];

            $role = $this->rolesModel->create($data);

            logActivity(
                "Create",
                "Add Role ' . $role->role_name . '",
                "roles",
                $role->id,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan role baru',
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
            $role = $this->rolesModel->findBy('id', $id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $role
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
            $oldData = $this->rolesModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'role_name' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Role Name is required.',
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $newData = [
                'role_name' => $validation['data']['role_name']
            ];

            $role = $this->rolesModel->update($id, $newData);

            logActivity(
                "Update",
                "User {$validation['data']['role_name']} berhasil diperbarui",
                "users",
                $id,
                $oldData,
                $newData
            );


            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data role',
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
            $role = $this->rolesModel->find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $this->rolesModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data role',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
