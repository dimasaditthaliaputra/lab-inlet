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

    public function countByType($type)
    {
        return $this->db
            ->query("SELECT COUNT(id) as total FROM {$this->table} WHERE type = :type")
            ->bind(':type', $type)
            ->fetch()->total;
    }

    public function getByTypePaginated($type, $limit, $offset)
    {
        return $this->db
            ->query("SELECT * FROM {$this->table} WHERE type = :type ORDER BY upload_date DESC LIMIT :limit OFFSET :offset")
            ->bind(':type', $type)
            ->bind(':limit', (int) $limit, \PDO::PARAM_INT)
            ->bind(':offset', (int) $offset, \PDO::PARAM_INT)
            ->fetchAll();
    }
}
