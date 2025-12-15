<?php

namespace App\Models;

use Core\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    public function getDataAll() {
        return $this->db->query("
            SELECT 
                m.*, 
                u.username,
                u.full_name AS user_full_name 
            FROM {$this->table} m 
            LEFT JOIN users u ON m.id = u.mahasiswa_id
            ORDER BY m.nim
        ")->fetchAll();
    }

    public function getMahasiswaById($id) {
        $sql = "
            SELECT 
                m.*, 
                u.id AS user_id,
                u.username
            FROM {$this->table} m
            LEFT JOIN users u ON m.id = u.mahasiswa_id
            WHERE m.id = :id
        ";
        return $this->db->query($sql)
            ->bind(':id', $id)
            ->fetch();
    }
    
    public function getUnassignedMahasiswa()
    {      
        $sql = "
            SELECT 
                m.id, 
                m.nim, 
                m.full_name 
            FROM {$this->table} m
            LEFT JOIN users u ON m.id = u.mahasiswa_id
            WHERE u.mahasiswa_id IS NULL OR u.id = 0 -- id=0 hanya untuk berjaga-jaga
            ORDER BY m.nim
        ";
        return $this->db->query($sql)->fetchAll();
    }
}