<?php

namespace App\Models;

use Core\Model;

class AttendancePermissions extends Model
{
    protected $table = 'attendance_permissions';

    public function getAllWithMahasiswa()
    {
        return $this->db->query("
            SELECT
                ap.id,
                ap.permission_type,
                ap.start_date,
                ap.end_date,
                ap.status,
                m.full_name AS mahasiswa_name
            FROM {$this->table} ap
            LEFT JOIN mahasiswa m ON m.id = ap.mahasiswa_id
            ORDER BY ap.id DESC
        ")->fetchAll();
    }

    public function getDetail($id)
    {
        return $this->db->query("
            SELECT
                ap.*,
                m.full_name AS mahasiswa_name,
                m.nim AS mahasiswa_nim,
                u.full_name AS approved_by_name
            FROM {$this->table} ap
            LEFT JOIN mahasiswa m ON m.id = ap.mahasiswa_id
            LEFT JOIN users u ON u.id = ap.approved_by
            WHERE ap.id = :id
            LIMIT 1
        ")->bind(':id', $id)->fetch();
    }

    public function approve($id, $approvedBy)
    {
        return $this->update($id, [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'rejection_note' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function reject($id, $approvedBy, $note)
    {
        return $this->update($id, [
            'status' => 'rejected',
            'approved_by' => $approvedBy,
            'rejection_note' => $note,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function isPermittedToday($mahasiswaId)
    {
        $currentDate = date('Y-m-d');
        
        $sql = "SELECT * FROM {$this->table}
                WHERE mahasiswa_id = :mahasiswa_id
                AND status = 'approved'
                AND start_date <= :current_date
                AND end_date >= :current_date
                LIMIT 1";

        return $this->db->query($sql)
            ->bind(':mahasiswa_id', $mahasiswaId)
            ->bind(':current_date', $currentDate)
            ->fetch();
    }

    public function getByMahasiswaId($mahasiswaId)
    {
        return $this->db->query("
            SELECT * FROM {$this->table} 
            WHERE mahasiswa_id = :id 
            ORDER BY created_at DESC
        ")->bind(':id', $mahasiswaId)->fetchAll();
    }
}
