<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected $table = 'product';

    public function getAll()
    {
        return $this->db->query("
            SELECT 
                id,
                product_name,
                description,
                image_name,
                release_date,
                feature,
                specification
            FROM {$this->table}
            ORDER BY release_date DESC, id DESC
        ")->fetchAll();
    }
}
