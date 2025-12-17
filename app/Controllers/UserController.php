<?php

namespace App\Controllers;

use App\Models\Mahasiswa;
use App\Models\Roles;
use App\Models\User;
use App\Models\Permissions;
use Core\Controller;

class UserController extends Controller
{
    protected $userModel;
    protected $permissionsModel;
    protected $mahasiswaModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->userModel = new User();
        $this->permissionsModel = new Permissions();
        $this->mahasiswaModel = new Mahasiswa();
    }

    public function index()
    {
        $role = new Roles();
        $mahasiswaModel = new Mahasiswa(); // Gunakan model mahasiswa

        $roles = $role->orderBy('role_name', 'ASC');

        $mahasiswaList = $mahasiswaModel->getUnassignedMahasiswa(); // Ambil daftar mahasiswa

        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/user');

        $data = [
            'title' => 'User',
            'roles' => $roles,
            'access' => $access,
            'mahasiswaList' => $mahasiswaList
        ];

        view_with_layout('admin/user/index', $data);
    }

    public function data()
    {
        try {
            $users = $this->userModel->getDataAll();

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
                ],
                'mahasiswa_id' => [
                    'required' => false,
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
                'id_roles' => $validation['data']['id_roles'],
                'mahasiswa_id' => $validation['data']['mahasiswa_id'] ?: null
            ];

            $insertId = $this->userModel->create($data);

            $user = $this->userModel->find($insertId);

            logActivity(
                "Create",
                "User " . $validation['data']['username'] . " successfully created",
                "users",
                $user->id,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan user baru',
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
                    'message' => 'Data user tidak ditemukan.'
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
                ],
                'mahasiswa_id' => [ // Validasi untuk Mahasiswa ID (opsional)
                    'required' => false,
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
                'id_roles' => $validation['data']['id_roles'],
                'mahasiswa_id' => $validation['data']['mahasiswa_id'] ?: null
            ];

            if (!empty($validation['data']['password'])) {
                $newData['password'] = password_hash($validation['data']['password'], PASSWORD_DEFAULT);
            }

            $user = $this->userModel->update($id, $newData);

            logActivity(
                "Update",
                "User {$validation['data']['username']} successfully updated",
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

    public function profile($id)
    {
        $user = $this->userModel->find($id);

        $data =  [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $user->full_name
        ];

        $data = [
            'title' => 'Profile',
            'user' => $data,
        ];

        view_with_layout('admin/profile/index', $data);
    }

    public function updateProfile($id)
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
                "Roles {$validation['data']['username']} successfully updated",
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

            logActivity(
                "Delete",
                "User {$user->username} successfully deleted",
                "users",
                $id,
                $user,
                null
            );

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
