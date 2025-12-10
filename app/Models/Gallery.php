<?php

namespace App\Models;

use Core\Model;

class Gallery extends Model
{
    protected $table = 'gallery';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY upload_date DESC")->fetchAll();
    }

    public function getByType($type)
    {
        return $this->db
            ->query("SELECT * FROM {$this->table} WHERE type = :type ORDER BY upload_date DESC")
            ->bind(':type', $type)
            ->fetchAll();
    }
}
