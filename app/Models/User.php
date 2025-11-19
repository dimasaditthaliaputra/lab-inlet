<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';

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
        $data = $this->db->query("
        SELECT 
            u.id AS user_id,
            u.username,
            u.email,
            u.full_name,
            u.id_roles AS user_role_id,
            r.id AS role_id,
            r.role_name
        FROM users u
        JOIN roles r ON u.id_roles = r.id
        WHERE u.id = :id
    ")
            ->bind(':id', $id)
            ->first();

        return [
            "user_id"   => $data->user_id,
            "username"  => $data->username,
            "email"     => $data->email,
            "full_name" => $data->full_name,

            "roles" => [
                "id"        => $data->role_id,
                "role_name" => $data->role_name
            ]
        ];
    }

    public function getRoles($id)
    {
        return $this->db->query("SELECT r.role_name FROM users u JOIN roles r ON u.id_roles = r.id WHERE u.id = :id")
            ->bind(':id', $id)
            ->first();
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
            ->first();

        if ($user) {
            if (hash_equals($user->remember_token, hash('sha256', $token))) {
                unset($user->password);
                unset($user->remember_token);
                return $user;
            }
        }

        return false;
    }
}
