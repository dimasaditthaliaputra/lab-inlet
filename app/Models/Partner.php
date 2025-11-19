<?php

namespace App\Models;

use Core\Model;

class Partner extends Model
{
    protected $table = 'partner';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY \"partner_name\"")->fetchAll();
    }
}