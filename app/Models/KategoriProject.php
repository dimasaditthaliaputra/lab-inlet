<?php

namespace App\Models;

use Core\Model;

class KategoriProject extends Model
{
    protected $table = 'category_project';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY \"name\"")->fetchAll();
    }
}