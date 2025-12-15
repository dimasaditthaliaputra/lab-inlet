<?php

namespace App\Controllers;

use App\Models\Mahasiswa;
use App\Models\Permissions;
use Core\Controller;

class MahasiswaController extends Controller
{
    protected $mahasiswaModel;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->mahasiswaModel = new Mahasiswa();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/mahasiswa');

        $data = [
            'title' => 'Manajemen Mahasiswa',
            'access' => $access
        ];

        view_with_layout('admin/mahasiswa/index', $data);
    }

    public function data()
    {
        try {
            $mahasiswa = $this->mahasiswaModel->getDataAll();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $mahasiswa
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
                'nim' => ['required' => true, 'messages' => ['required' => 'NIM wajib diisi.']],
                'full_name' => ['required' => true, 'messages' => ['required' => 'Nama Lengkap wajib diisi.']],
                'university' => ['required' => true, 'messages' => ['required' => 'Universitas wajib diisi.']],
                'study_program' => ['required' => true, 'messages' => ['required' => 'Program Studi wajib diisi.']],
                'entry_year' => ['required' => true, 'messages' => ['required' => 'Tahun Masuk wajib diisi.']],
                'current_semester' => ['required' => true, 'messages' => ['required' => 'Semester wajib diisi.']],
                'phone_number' => ['required' => true, 'messages' => ['required' => 'Nomor HP wajib diisi.']],
                'address' => ['required' => true, 'messages' => ['required' => 'Alamat wajib diisi.']],
                'status' => ['required' => true, 'messages' => ['required' => 'Status wajib diisi.']],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $data = [
                'nim' => $validation['data']['nim'],
                'full_name' => $validation['data']['full_name'],
                'university' => $validation['data']['university'],
                'study_program' => $validation['data']['study_program'],
                'entry_year' => $validation['data']['entry_year'],
                'current_semester' => $validation['data']['current_semester'],
                'phone_number' => $validation['data']['phone_number'],
                'address' => $validation['data']['address'],
                'status' => $validation['data']['status'],
            ];

            $insertId = $this->mahasiswaModel->create($data);
            $mahasiswa = $this->mahasiswaModel->find($insertId);

            logActivity(
                "Create",
                "Data Mahasiswa ({$mahasiswa->full_name} - {$mahasiswa->nim}) berhasil ditambahkan",
                "mahasiswa",
                $mahasiswa->id,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan data mahasiswa',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $mahasiswa = $this->mahasiswaModel->find($id);

            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $mahasiswa
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
            $oldData = $this->mahasiswaModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'nim' => ['required' => true, 'messages' => ['required' => 'NIM wajib diisi.']],
                'full_name' => ['required' => true, 'messages' => ['required' => 'Nama Lengkap wajib diisi.']],
                'university' => ['required' => true, 'messages' => ['required' => 'Universitas wajib diisi.']],
                'study_program' => ['required' => true, 'messages' => ['required' => 'Program Studi wajib diisi.']],
                'entry_year' => ['required' => true, 'messages' => ['required' => 'Tahun Masuk wajib diisi.']],
                'current_semester' => ['required' => true, 'messages' => ['required' => 'Semester wajib diisi.']],
                'phone_number' => ['required' => true, 'messages' => ['required' => 'Nomor HP wajib diisi.']],
                'address' => ['required' => true, 'messages' => ['required' => 'Alamat wajib diisi.']],
                'status' => ['required' => true, 'messages' => ['required' => 'Status wajib diisi.']],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $newData = [
                'nim' => $validation['data']['nim'],
                'full_name' => $validation['data']['full_name'],
                'university' => $validation['data']['university'],
                'study_program' => $validation['data']['study_program'],
                'entry_year' => $validation['data']['entry_year'],
                'current_semester' => $validation['data']['current_semester'],
                'phone_number' => $validation['data']['phone_number'],
                'address' => $validation['data']['address'],
                'status' => $validation['data']['status'],
            ];

            $this->mahasiswaModel->update($id, $newData);
            $updatedData = $this->mahasiswaModel->find($id);

            logActivity(
                "Update",
                "Data Mahasiswa ({$updatedData->full_name} - {$updatedData->nim}) berhasil diperbarui",
                "mahasiswa",
                $id,
                $oldData,
                $updatedData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data mahasiswa',
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
            $mahasiswa = $this->mahasiswaModel->find($id);

            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ], 404);
            }

            // PENTING: Perlu dicek apakah mahasiswa ini terikat dengan user sebelum dihapus
            // (Diasumsikan ON DELETE CASCADE di FK Users.mahasiswa_id sudah ditangani)

            logActivity(
                "Delete",
                "Data Mahasiswa ({$mahasiswa->full_name} - {$mahasiswa->nim}) berhasil dihapus",
                "mahasiswa",
                $id,
                $mahasiswa,
                null
            );

            $this->mahasiswaModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data mahasiswa',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}