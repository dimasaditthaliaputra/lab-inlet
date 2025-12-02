<?php

namespace App\Controllers;

use App\Models\KategoriProject;
use App\Models\Permissions;
use Core\Controller;

class KategoriProjectController extends Controller
{
    protected $kategoriModel;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->kategoriModel = new KategoriProject();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/kategori-project');
        $data = [
            'title' => 'Kategori Project',
            'access' => $access
        ];

        view_with_layout('admin/kategori_project/index', $data);
    }

    public function data()
    {
        try {
            $categories = $this->kategoriModel->getAll();
            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }, $categories);

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

    public function store()
    {
        try {
            $validation = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama kategori wajib diisi']
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
                'name' => $validation['data']['name']
            ];

            $insertId = $this->kategoriModel->create($data);

            logActivity(
                "Create",
                "Kategori Project '{$data['name']}' successfully created",
                "kategori_project",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan kategori baru',
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
            $kategori = $this->kategoriModel->findBy('id', $id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'id' => $kategori->id,
                'name' => $kategori->name,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $data
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
            $kategori = $this->kategoriModel->find($id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kategori tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama kategori wajib diisi']
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
                'name' => $validation['data']['name']
            ];

            $this->kategoriModel->update($id, $data);

            logActivity(
                "Update",
                "Kategori Project '{$data['name']}' successfully updated",
                "kategori_project",
                $id,
                $kategori,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data kategori',
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
            $kategori = $this->kategoriModel->find($id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kategori tidak ditemukan.'
                ], 404);
            }

            logActivity(
                "Delete",
                "Kategori Project {$kategori->name} successfully deleted",
                "kategori_project",
                $id,
                $kategori,
                null
            );

            $this->kategoriModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data kategori',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}