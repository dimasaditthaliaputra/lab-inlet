<?php

namespace App\Models;

use Core\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY \"role_name\"")->all();
    }
}