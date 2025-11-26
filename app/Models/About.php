<?php

namespace App\Models;

use Core\Model;

class About extends Model
{
    protected $table = 'aboutus';

    public function getAll()
    {
        return $this->db->query("
        SELECT 
            ab.id AS id_about,
            ab.title,
            ab.description,
            ab.vision,
            ab.mission,
            ai.id AS id_image,
            ai.aboutus_id,
            ai.image_name
        FROM {$this->table} ab
        JOIN aboutusimages ai ON ab.id = ai.aboutus_id
        WHERE ai.aboutus_id = 1
    ")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("
        SELECT 
            ab.id AS id_about,
            ab.title,
            ab.description,
            ab.vision,
            ab.mission,
            ai.id AS id_image,
            ai.aboutus_id,
            ai.image_name
        FROM {$this->table} ab
        JOIN aboutusimages ai ON ab.id = ai.aboutus_id
        WHERE ai.aboutus_id = $id
    ")->fetchAll();
    }
}
