<?php

namespace App\Models;

use Core\Model;

class AttendanceSetting extends Model
{
    protected $table = 'attendance_settings';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetch();
    }
}