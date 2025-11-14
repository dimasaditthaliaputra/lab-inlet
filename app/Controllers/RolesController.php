<?php

namespace App\Controllers;

use App\Models\Roles;
use Core\Controller;

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
            'title' => 'Role Pengguna',
        ];

        view_with_layout('admin/roles/index', $data);
    }

    public function data()
    {
        try {
            $roles = $this->rolesModel->getAll();

            if (!$roles) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

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
            $form = request('role_name');

            $role = $this->rolesModel->create([
                'role_name' => $form
            ]);

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
            $role = $this->rolesModel->find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $form = request('role_name');

            $this->rolesModel->update($id, [
                'role_name' => $form
            ]);

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
