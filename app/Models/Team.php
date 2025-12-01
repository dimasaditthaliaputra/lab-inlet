<?php

namespace App\Models;

use Core\Model;

class Team extends Model
{
    protected $table = 'team_member';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC")->fetchAll();
    }
}
