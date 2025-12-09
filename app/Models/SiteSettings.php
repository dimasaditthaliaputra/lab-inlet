<?php

namespace App\Models;

use Core\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = 1")->fetch();
    }

    public function getConfig($column = '*')
    {
        $column = is_array($column) ? implode(',', $column) : $column;

        $stmt = $this->db->query("SELECT $column FROM {$this->table} WHERE id = 1 LIMIT 1");
        return $stmt->fetch();
    }
}
