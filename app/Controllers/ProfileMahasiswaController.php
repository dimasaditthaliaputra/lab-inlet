<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Permissions;
use Core\Controller;

class ProfileMahasiswaController extends Controller
{
    protected $userModel;
    protected $permissionsModel;
    protected $mahasiswaModel; // Opsional, jika Anda butuh akses langsung ke model Mahasiswa

    public function __construct()
    {
        // Pastikan pengguna sudah login
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        // Memastikan hanya role Mahasiswa (ID 3) yang boleh mengakses, jika diperlukan
        $user = session('user');
        if ($user->id_roles != 3) {
             // Opsional: Redirect atau tampilkan error jika bukan Mahasiswa
             redirect(base_url('admin/dashboard'));
             exit;
        }
        
        $this->userModel = new User();
        $this->permissionsModel = new Permissions();
    }

    /**
     * Menampilkan halaman profil Mahasiswa.
     */
    public function index()
    {
        $user = session('user');
        $userId = $user->id ?? 0;
        
        // Asumsi: Model User memiliki metode yang mengambil data user dan mahasiswa yang terhubung.
        // Data yang diambil harus mencakup semua kolom yang dibutuhkan dari tabel users dan mahasiswa.
        $profileData = $this->userModel->getUserWithMahasiswaData($userId);

        if (!$profileData) {
            // Jika data tidak ditemukan, redirect atau tampilkan error
            redirect(base_url('mahasiswa/dashboard'));
            exit;
        }

        $data = [
            'title' => 'Profile Mahasiswa',
            'profile' => $profileData,
            // Permissions tidak diperlukan di sini karena ini adalah halaman personal
        ];

        view_with_layout_mahasiswa('mahasiswa/profile/index', $data);
    }

    /**
     * Mengelola permintaan PUT/POST untuk memperbarui profile.
     */
    public function update()
    {
        $userId = session('user')->id ?? 0;
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        // 1. Validasi Input
        $validation = validate([
            'username' => ['required' => true],
            'email' => ['required' => true],
            'full_name' => ['required' => true],
            'phone_number' => ['required' => true], 
            'address' => ['required' => false],     
            'password' => ['required' => false],
            // ... tambahkan validasi lain sesuai kebutuhan
        ]);

        if (!$validation['success']) {
            return response()->json(['success' => false, 'errors' => $validation['errors']], 422);
        }

        try {
            // Ambil data lama untuk logging
            $oldData = $this->userModel->getUserWithMahasiswaData($userId);

            // 2. Pisahkan data untuk tabel users dan mahasiswa
            $userData = [
                'username' => $validation['data']['username'],
                'email' => $validation['data']['email'],
                'full_name' => $validation['data']['full_name'],
            ];
            
            if (!empty($validation['data']['password'])) {
                $userData['password'] = password_hash($validation['data']['password'], PASSWORD_DEFAULT);
            }

            $mahasiswaData = [
                'phone_number' => $validation['data']['phone_number'],
                'address' => $validation['data']['address'],
                // NIM, study_program, entry_year tidak boleh diubah di sini
            ];
            
            // 3. Update data users
            $this->userModel->update($userId, $userData);

            // 4. Update data mahasiswa
            $mahasiswaId = $oldData['mahasiswa_id'] ?? null;
            if ($mahasiswaId) {
                (new \App\Models\Mahasiswa())->update($mahasiswaId, $mahasiswaData);
            }

            logActivity(
                "Update",
                "Profile Mahasiswa ({$userData['username']}) berhasil diperbarui",
                "users",
                $userId,
                $oldData,
                $this->userModel->getUserWithMahasiswaData($userId)
            );

            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.'], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }
}