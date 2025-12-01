<?php

namespace App\Models;

use Core\Model;

class ResearchFocus extends Model
{
    protected $table = 'research_focus';

    public function getAll()
    {
        return $this->db->query("
            SELECT *
            FROM {$this->table}
            ORDER BY sort_order ASC, title ASC
        ")->fetchAll();
    }
}