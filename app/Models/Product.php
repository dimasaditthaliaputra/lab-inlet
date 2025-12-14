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
                specification,
                product_link
            FROM {$this->table}
            ORDER BY release_date DESC, id DESC
        ")->fetchAll();
    }

    public function getLimit(int $limit = 2, int $offset = 0): array
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
            LIMIT {$limit} OFFSET {$offset}
        ")->fetchAll();
    }
}
