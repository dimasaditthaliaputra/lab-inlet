<?php

namespace App\Models;

use Core\Model;

class Facilities extends Model
{
    protected $table = 'facilities';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }
}