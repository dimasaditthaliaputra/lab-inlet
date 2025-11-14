<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'users';

    public function all(){
        return $this->db->query("SELECT * FROM {$this->table}")->all();
    }

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function findByUsername($username)
    {
        return $this->findBy('username', $username);
    }

    public function getActiveUsers()
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE status = :status")
            ->bind(':status', 'active')
            ->all();
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
            ->single();

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
