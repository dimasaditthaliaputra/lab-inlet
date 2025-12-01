<?php

namespace App\Models;

use Core\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }
}