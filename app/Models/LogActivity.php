<?php

namespace App\Models;

use Core\Model;

class LogActivity extends Model
{
    protected $table = 'activity_log';

    public function getAll()
    {
        return $this->db->query("SELECT a.id, u.username as username, a.action_type, a.table_name, a.record_id, a.description, a.old_data, a.new_data, a.created_at 
            FROM {$this->table} a 
            JOIN users u ON a.id_user = u.id
            ORDER BY a.created_at DESC"
        )->fetchAll();
    }
}
