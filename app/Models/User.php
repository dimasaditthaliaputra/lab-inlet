<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';
    public function getDataAll()
    {
        return $this->db->query("
        SELECT 
            u.*, 
            r.role_name as roles,
            m.nim, -- Ambil NIM dari tabel mahasiswa yang terhubung melalui mahasiswa_id
            m.study_program
        FROM {$this->table} u 
        JOIN roles r ON u.id_roles = r.id 
        LEFT JOIN mahasiswa m ON u.mahasiswa_id = m.id -- **JOIN MENGGUNAKAN u.mahasiswa_id**
        WHERE u.id != 9 
        order by \"username\"
    ")->fetchAll();
    }

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function findByUsername($username)
    {
        return $this->findBy('username', $username);
    }

    public function getUserWithRoles($id)
    {
        // Ambil detail user, role, dan detail Mahasiswa yang terhubung
        $data = $this->db->query("
    SELECT 
        u.id AS user_id,
        u.username,
        u.email,
        u.full_name,
        u.id_roles AS user_role_id,
        u.mahasiswa_id, -- **Ambil ID Mahasiswa dari tabel users**
        r.id AS role_id,
        r.role_name,
        m.nim,
        m.full_name AS mahasiswa_name -- Nama lengkap mahasiswa dari tabel mahasiswa
    FROM users u
    JOIN roles r ON u.id_roles = r.id
    LEFT JOIN mahasiswa m ON u.mahasiswa_id = m.id -- **LEFT JOIN ke mahasiswa menggunakan u.mahasiswa_id**
    WHERE u.id = :id
")
            ->bind(':id', $id)
            ->fetch();

        if (!$data) return null;

        $result = [
            "user_id"   => $data->user_id,
            "username"  => $data->username,
            "email"     => $data->email,
            "full_name" => $data->full_name,
            "mahasiswa_id" => $data->mahasiswa_id, // Tambahkan mahasiswa_id
            "roles" => [
                "id"        => $data->role_id,
                "role_name" => $data->role_name
            ]
        ];

        // Tambahkan data mahasiswa jika ada relasi
        if ($data->mahasiswa_id !== null) {
            $result['mahasiswa'] = [
                'id' => $data->mahasiswa_id,
                'nim' => $data->nim,
                'mahasiswa_name' => $data->mahasiswa_name
            ];
        }

        return $result;
    }

    public function getRoles($id)
    {
        return $this->db->query("SELECT r.role_name FROM users u JOIN roles r ON u.id_roles = r.id WHERE u.id = :id")
            ->bind(':id', $id)
            ->fetch();
    }

    public function getActiveUsers()
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE status = :status")
            ->bind(':status', 'active')
            ->fetchAll();
    }

    public function createUser($data)
    {
        // Hash password sebelum menyimpan
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->create($data);
    }

    public function verifyLogin($username, $password)
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user->password)) {
            $roleData = $this->getRoles($user->id);

            if ($roleData) {
                $user->role_name = $roleData->role_name;
            } else {
                $user->role_name = '';
            }

            return $user;
        }

        return false;
    }

    public function updateRememberToken($user_id, $token_hash, $expires_at)
    {
        $sql = "UPDATE {$this->table} SET remember_token = :token, remember_token_expires_at = :expires WHERE id = :id";

        return $this->db->query($sql)
            ->bind(':token', $token_hash)
            ->bind(':expires', $expires_at)
            ->bind(':id', $user_id)
            ->execute();
    }

    public function findByRememberToken($user_id, $token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND remember_token IS NOT NULL AND remember_token_expires_at > NOW()";

        $user = $this->db->query($sql)
            ->bind(':id', $user_id)
            ->fetch();

        if ($user) {
            if (hash_equals($user->remember_token, hash('sha256', $token))) {
                unset($user->password);
                unset($user->remember_token);
                return $user;
            }
        }

        return false;
    }

    public function getUserWithMahasiswaData($id)
    {
        $data = $this->db->query("
            SELECT 
                u.id AS user_id,
                u.username,
                u.email,
                u.full_name,
                u.id_roles AS user_role_id,
                u.mahasiswa_id,
                r.id AS role_id,
                r.role_name,
                
                -- Data Mahasiswa
                m.id AS mahasiswa_id_m,
                m.nim,
                m.full_name AS mahasiswa_name,
                m.university,
                m.study_program,
                m.entry_year,
                m.current_semester,
                m.phone_number,
                m.address
            FROM users u
            JOIN roles r ON u.id_roles = r.id
            LEFT JOIN mahasiswa m ON u.mahasiswa_id = m.id
            WHERE u.id = :id
        ")
            ->bind(':id', $id)
            ->fetch();

        if (!$data) return null;

        $result = [
            "user_id"   => $data->user_id,
            "username"  => $data->username,
            "email"     => $data->email,
            "full_name" => $data->full_name,
            "mahasiswa_id" => $data->mahasiswa_id,
            "roles" => [
                "id"        => $data->role_id,
                "role_name" => $data->role_name
            ]
        ];

        // Tambahkan detail mahasiswa jika ada relasi
        if ($data->mahasiswa_id !== null) {
            $result['mahasiswa'] = [
                'id'                => $data->mahasiswa_id_m,
                'nim'               => $data->nim,
                'mahasiswa_name'    => $data->mahasiswa_name,
                'university'        => $data->university,
                'study_program'     => $data->study_program,
                'entry_year'        => $data->entry_year,
                'current_semester'  => $data->current_semester,
                'phone_number'      => $data->phone_number,
                'address'           => $data->address
            ];
        }

        return $result;
    }
}
