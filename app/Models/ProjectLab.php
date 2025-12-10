<?php

namespace App\Models;

use Core\Model;

class ProjectLab extends Model
{
    protected $table = 'project_lab';

    public function getAll()
    {
        return $this->db->query("
            SELECT 
                p.*, 
                STRING_AGG(k.name, ', ') as kategori_name 
            FROM {$this->table} p 
            LEFT JOIN project_category_pivot pcp ON p.id = pcp.project_id
            LEFT JOIN category_project k ON pcp.category_id = k.id 
            GROUP BY p.id
            ORDER BY p.name
        ")->fetchAll();
    }

    public function getLimit(int $limit = 5, int $offset = 0): array
    {
        return $this->db->query("
        SELECT 
            p.*, 
            STRING_AGG(k.name, ', ') AS kategori_name
        FROM {$this->table} p
        LEFT JOIN project_category_pivot pcp ON p.id = pcp.project_id
        LEFT JOIN category_project k ON pcp.category_id = k.id
        GROUP BY p.id
        ORDER BY p.name
        LIMIT {$limit} OFFSET {$offset}
    ")->fetchAll();
    }


    public function getProjectWithCategories($id)
    {
        $project = $this->db->query("SELECT * FROM {$this->table} WHERE id = :id")
            ->bind(':id', $id)
            ->fetch();

        if (!$project) return null;

        $categories = $this->db->query("
            SELECT category_id 
            FROM project_category_pivot 
            WHERE project_id = :id
        ")->bind(':id', $id)->fetchAll();

        $catIds = array_map(function ($c) {
            return $c->category_id;
        }, $categories);

        $imageRaw = $project->image_url;
        $imageList = [];
        if ($imageRaw && strpos($imageRaw, '{') === 0) {
            $cleaned = trim($imageRaw, '{}');
            if (!empty($cleaned)) {
                $images = explode(',', $cleaned);
                foreach ($images as $img) {
                    $cleanImg = trim($img, '"');
                    $imageList[] = asset('uploads/project_images/') . $cleanImg;
                }
            }
        } elseif ($imageRaw) {
            $imageList[] = asset('uploads/project_images/') . $imageRaw;
        }

        return [
            "id"            => $project->id,
            "name"          => $project->name,
            "description"   => $project->description,
            "video_url"     => $project->video_url,
            "images_list"   => $imageList,
            "status"        => $project->status,
            "category_ids"  => $catIds
        ];
    }

    public function assignCategories($projectId, $categoryIds)
    {
        $this->db->query("DELETE FROM project_category_pivot WHERE project_id = :pid")
            ->bind(':pid', $projectId)
            ->execute();

        if (!empty($categoryIds) && is_array($categoryIds)) {
            foreach ($categoryIds as $catId) {
                $this->db->query("INSERT INTO project_category_pivot (project_id, category_id) VALUES (:pid, :cid)")
                    ->bind(':pid', $projectId)
                    ->bind(':cid', $catId)
                    ->execute();
            }
        }
    }
}
