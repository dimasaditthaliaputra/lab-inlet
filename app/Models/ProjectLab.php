<?php

namespace App\Models;

use Core\Model;

class ProjectLab extends Model
{
    protected $table = 'project_lab';

    public function getAll()
    {
        return $this->db->query("
            SELECT p.*, k.name as kategori_name 
            FROM {$this->table} p 
            LEFT JOIN category_project k ON p.id_kategori = k.id 
            ORDER BY p.name
        ")->fetchAll();
    }
}