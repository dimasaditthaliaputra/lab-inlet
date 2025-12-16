<?php

namespace App\Models;

use Core\Model;

class AttendanceLog extends Model
{
    protected $table = 'attendance_logs';

    /**
     * Mendapatkan log absensi terakhir (check-in atau check-out) untuk mahasiswa hari ini.
     * @param int $mahasiswaId ID Mahasiswa
     * @return object|null Log terbaru hari ini
     */
    public function getLatestLogByMahasiswaId($mahasiswaId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE mahasiswa_id = :mahasiswa_id 
                AND DATE(log_time) = CURRENT_DATE 
                ORDER BY log_time DESC 
                LIMIT 1";
        
        return $this->db->query($sql)
            ->bind(':mahasiswa_id', $mahasiswaId)
            ->fetch();
    }

    /**
     * Memeriksa apakah mahasiswa sudah check-in hari ini.
     * @param int $mahasiswaId ID Mahasiswa
     * @return bool
     */
    public function hasCheckedInToday($mahasiswaId)
    {
        $sql = "SELECT id FROM {$this->table} 
                WHERE mahasiswa_id = :mahasiswa_id 
                AND log_type = 'check_in' 
                AND DATE(log_time) = CURRENT_DATE 
                LIMIT 1";
                
        return (bool) $this->db->query($sql)
            ->bind(':mahasiswa_id', $mahasiswaId)
            ->fetch();
    }

    /**
     * Memeriksa apakah mahasiswa sudah check-out hari ini.
     * @param int $mahasiswaId ID Mahasiswa
     * @return bool
     */
    public function hasCheckedOutToday($mahasiswaId)
    {
        $sql = "SELECT id FROM {$this->table} 
                WHERE mahasiswa_id = :mahasiswa_id 
                AND log_type = 'check_out' 
                AND DATE(log_time) = CURRENT_DATE 
                LIMIT 1";
                
        return (bool) $this->db->query($sql)
            ->bind(':mahasiswa_id', $mahasiswaId)
            ->fetch();
    }
}