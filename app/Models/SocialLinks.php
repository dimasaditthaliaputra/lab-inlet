<?php

namespace App\Models;

use Core\Model;

class SocialLinks extends Model
{
    protected $table = 'social_links';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC")->fetchAll();
    }
}
