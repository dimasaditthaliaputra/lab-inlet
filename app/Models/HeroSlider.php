<?php

namespace App\Models;

use Core\Model;

class HeroSlider extends Model
{
    protected $table = 'hero_slider';

    public function getAll()
    {
        return $this->db->query("
            SELECT *
            FROM {$this->table}
            ORDER BY sort_order ASC, created_at DESC
        ")->fetchAll();
    }
}
