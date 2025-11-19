<?php

namespace App\Models;

use Core\Model;

class Team extends Model
{
    protected $table = 'team';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }
}