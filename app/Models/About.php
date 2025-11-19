<?php

namespace App\Models;

use Core\Model;

class About extends Model
{
    protected $table = 'aboutus';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} LIMIT 1")->fetchAll();
    }
}