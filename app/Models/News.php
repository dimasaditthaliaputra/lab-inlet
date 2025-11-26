<?php

namespace App\Models;

use Core\Model;

class News extends Model
{
    protected $table = 'news';

    public function getAll()
    {
        return $this->db->query("SELECT n.id, n.title, n.image_name, n.content, u.full_name as created_by, n.publish_date, n.is_publish
         FROM {$this->table} n 
         LEFT JOIN users u ON n.created_by = u.id
         ORDER BY \"id\"")->fetchAll();
    }
}
