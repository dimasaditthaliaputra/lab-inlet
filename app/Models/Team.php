<?php

namespace App\Models;

use Core\Model;

class Team extends Model
{
    protected $table = 'team_member';

    public function getAll()
    {
        $data = $this->db->query("
        SELECT 
            tm.id,
            tm.full_name,
            tm.nip,
            tm.nidn,
            tm.lab_position,
            tm.academic_position,
            tm.study_program,
            tm.email,
            tm.office_address,
            tm.image_name,
            tm.expertise,
            tm.education,
            tm.certifications,
            tm.courses_taught,
            tm.created_at,
            tm.updated_at,
            COALESCE(
                STRING_AGG(sl.name || '|' || st.link_sosmed, ', '),
            '') AS social_medias
        FROM {$this->table} tm
        LEFT JOIN social_team st ON tm.id = st.id_team
        LEFT JOIN social_links sl ON st.id_social_media = sl.id
        GROUP BY tm.id
        ORDER BY 
            CASE 
                WHEN tm.lab_position = 'Kepala Laboratorium' THEN 1
                ELSE 2
            END,
            tm.id DESC
    ")->fetchAll();

        return $data;
    }

    public function getTeamDetail($id)
    {
        $team = $this->db->query("
            SELECT * FROM {$this->table} WHERE id = :id
        ")->bind(':id', $id)->fetch();

        if (!$team) return null;

        $socials = $this->db->query("
            SELECT 
                sl.id,
                sl.name,
                sl.icon_name,
                sl.image_cover,
                st.link_sosmed
            FROM social_team st
            JOIN social_links sl ON st.id_social_media = sl.id
            WHERE st.id_team = :id
        ")->bind(':id', $id)->fetchAll();

        return [
            "id" => $team->id,
            "full_name" => $team->full_name,
            "nip" => $team->nip,
            "nidn" => $team->nidn,
            "lab_position" => $team->lab_position,
            "academic_position" => $team->academic_position,
            "study_program" => $team->study_program,
            "email" => $team->email,
            "office_address" => $team->office_address,
            "image_name" => $team->image_name,
            "expertise" => $team->expertise,
            "education" => $team->education,
            "certifications" => $team->certifications,
            "courses_taught" => $team->courses_taught,
            "created_at" => $team->created_at,
            "updated_at" => $team->updated_at,
            "social_medias" => $socials
        ];
    }

    public function assignSocialMedias($teamId, $socialData)
    {
        $this->db->query("
            DELETE FROM social_team WHERE id_team = :tid
        ")
            ->bind(':tid', $teamId)
            ->execute();

        if (!empty($socialData) && is_array($socialData)) {
            foreach ($socialData as $item) {
                $this->db->query("
                    INSERT INTO social_team (id_team, id_social_media, link_sosmed)
                    VALUES (:tid, :sid, :link)
                ")
                    ->bind(':tid', $teamId)
                    ->bind(':sid', $item['id_social_media'])
                    ->bind(':link', $item['link_sosmed'])
                    ->execute();
            }
        }
    }

    public function getTeamWithCreator($id)
    {
        return $this->db->query(
            "SELECT t.*, u.* 
         FROM {$this->table} t 
         LEFT JOIN social_team s ON t.id = s.id_team
         WHERE t.id = :id",
        )
            ->bind(':id', $id)
            ->fetch();
    }
}
