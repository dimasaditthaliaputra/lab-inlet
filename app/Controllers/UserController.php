<?php

namespace App\Controllers;

use App\Models\Roles;
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
        $usersModel = new Roles();

        $users = $usersModel->orderBy('role_name', 'ASC');

        $data = [
            'title' => 'User',
            'roles' => $users
        ];

        view_with_layout('admin/user/index', $data);
    }

    public function data()
    {
        try {
            $users = $this->userModel->all();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $users
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
                'username' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Username is required.'
                    ]
                ],
                'email' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Email is required.'
                    ]
                ],
                'password' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Password is required.'
                    ]
                ],
                'full_name' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Full Name is required.'
                    ]
                ],
                'id_roles' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Roles is required'
                    ]
                ]
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $data = [
                'username' => $validation['data']['username'],
                'email' => $validation['data']['email'],
                'password' => password_hash($validation['data']['password'], PASSWORD_DEFAULT),
                'full_name' => $validation['data']['full_name'],
                'id_roles' => $validation['data']['id_roles']
            ];

            $user = $this->userModel->create($data);

            logActivity(
                "Create",
                "User {$validation['data']['username']} berhasil ditambahkan",
                "users",
                $user->id,
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
                'message' => 'Terjadi kesalahan pada server, coba lagi.' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userModel->getUserWithRoles($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.' . $e->getMessage()
            ], 500);
        }
    }

    public function update($id)
    {
        try {
            $oldData = $this->userModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'username' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Username is required.'
                    ]
                ],
                'email' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Email is required.'
                    ]
                ],
                'password' => [
                    'required' => false,
                ],
                'full_name' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Full Name is required.'
                    ]
                ],
                'id_roles' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Roles is required'
                    ]
                ]
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $newData = [
                'username' => $validation['data']['username'],
                'email' => $validation['data']['email'],
                'full_name' => $validation['data']['full_name'],
                'id_roles' => $validation['data']['id_roles']
            ];

            if (!empty($validation['data']['password'])) {
                $newData['password'] = password_hash($validation['data']['password'], PASSWORD_DEFAULT);
            }

            $user = $this->userModel->update($id, $newData);

            logActivity(
                "Update",
                "User {$validation['data']['username']} berhasil diperbarui",
                "users",
                $id,
                $oldData,
                $user
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
            $user = $this->userModel->find($id);

            if (!$user) {
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
