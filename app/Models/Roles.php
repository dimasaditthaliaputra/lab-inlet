<?php

namespace App\Models;

use Core\Model;

class Roles extends Model
{
    protected $table = 'roles';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY \"role_name\"")->all();
    }
}