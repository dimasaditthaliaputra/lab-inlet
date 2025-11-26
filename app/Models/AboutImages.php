<?php

namespace App\Models;

use Core\Model;

class AboutImages extends Model
{
    protected $table = 'aboutusimages';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table} LIMIT 3")->fetch();
    }

    public function getByAboutId($aboutus_id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE aboutus_id = :id")
            ->bind(':id', $aboutus_id)
            ->fetchAll();
    }
}