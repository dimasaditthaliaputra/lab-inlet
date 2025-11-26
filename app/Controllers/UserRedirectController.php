<?php

namespace App\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserRedirect;
use Core\Controller;

class UserRedirectController extends Controller
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect('admin/login');
            exit;
        }

        $this->userModel = new UserRedirect();
        $this->roleModel = new Roles();
    }

    public function index()
    {
        // Panggil Fungsi Get All dari Model User Redirect
        $users = $this->userModel->getAll();

        $data = [
            'title' => 'Daftar Pengguna',
            'users' => $users
        ];

        view_with_layout('admin/userRedirect/index', $data);
    }

    public function create()
    {
        // Panggil Fungsi All otomatis dari SuperClass Model
        // Akan otomatis membuat query SELECT * FROM {table dari model yang dipanggil}
        $roles = $this->roleModel->all();

        $data = [
            'title' => 'Tambah Pengguna Baru',
            'roles' => $roles
        ];

        view_with_layout('admin/userRedirect/create', $data);
    }

    public function store()
    {
        $data = [
            'username'  => request('username'),
            'email'     => request('email'),
            'full_name' => request('full_name'),
            'id_roles'  => request('id_roles'),
            'password'  => password_hash(request('password'), PASSWORD_DEFAULT)
        ];

        // Panggil Fungsi Create dari SuperClass Model
        // Akan otomatis membuat query INSERT INTO {table dari model yang dipanggil} ($data)
        if ($this->userModel->create($data)) {
            flash('success', 'User berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menyimpan data');
        }

        redirect('admin/userRedirect');
    }

    public function edit($id)
    {
        // Temukan user berdasarkan id
        // untuk ditampilkan di form
        $user = $this->userModel->find($id);

        if (!$user) {
            flash('error', 'User tidak ditemukan');
            redirect('admin/userRedirect');
            return;
        }

        $roles = $this->roleModel->all();

        $data = [
            'title' => 'Edit Pengguna',
            'user'  => $user,
            'roles' => $roles
        ];

        view_with_layout('admin/userRedirect/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            flash('error', 'User tidak ditemukan');
            redirect('admin/userRedirect');
            return;
        }

        $data = [
            'username'  => request('username'),
            'email'     => request('email'),
            'full_name' => request('full_name'),
            'id_roles'  => request('id_roles')
        ];

        $password = request('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Panggil Fungsi Update dari SuperClass Model
        if ($this->userModel->update($id, $data)) {
            flash('success', 'User berhasil diperbarui');
        } else {
            flash('error', 'Gagal memperbarui data');
        }

        redirect('admin/userRedirect');
    }

    public function destroy($id)
    {
        // Panggil Fungsi Find dari SuperClass Model
        // Akan otomatis membuat query Delete FROM {table dari model yang dipanggil} WHERE id = $id
        $user = $this->userModel->find($id);

        if (!$user) {
            flash('error', 'User tidak ditemukan');
        } else {
            if ($this->userModel->delete($id)) {
                flash('success', 'User successfully deleted');
            } else {
                flash('error', 'Gagal menghapus user');
            }
        }

        redirect('admin/userRedirect');
    }
}
