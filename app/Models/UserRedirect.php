<?php

namespace App\Models;

use Core\Model;

class UserRedirect extends Model
{
    protected $table = 'users';
    public function getAll() {
        return $this->db->query('SELECT u.*, r.role_name FROM users u JOIN roles r ON u.id_roles = r.id')->fetchAll();
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
            ->fetch();

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
}
