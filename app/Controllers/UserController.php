<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;

class UserController extends Controller
{
    protected $userModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->userModel = new User();
    }

    public function index()
    {
        $data = [
            'title' => 'User Pengguna',
        ];

        view_with_layout('admin/user/index', $data);
    }

    public function data()
    {
        try {
            $roles = $this->userModel->all();

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
            $username = request('username');
            $email = request('email');

            $role = $this->userModel->create([
                'role_name' => $username,
                'email' => $email
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
            $role = $this->userModel->findBy('id', $id);

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
            $role = $this->userModel->find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $form = request('role_name');

            $this->userModel->update($id, [
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
            $role = $this->userModel->find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $this->userModel->delete($id);

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
