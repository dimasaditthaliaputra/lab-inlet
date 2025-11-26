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
            WHERE a.id_user != 9
            ORDER BY a.created_at DESC
            LIMIT 25;"
        )->fetchAll();
    }

    public function getRecent() {
        $recent = $this->db->query("SELECT a.action_type, a.description, a.created_at 
            FROM {$this->table} a 
            JOIN users u ON a.id_user = u.id
            WHERE a.id_user != 9 AND a.action_type NOT IN ('Login', 'Log Out')
            ORDER BY a.created_at DESC
            LIMIT 4;"
        )->fetchAll();

        $data = array_map(function ($item) {
            return [
                'type' => $item->action_type,
                'description' => $item->description,
                'created_at' => date('d M Y H:i:s', strtotime($item->created_at))
            ];
        }, $recent);

        return $data;
    }
}
