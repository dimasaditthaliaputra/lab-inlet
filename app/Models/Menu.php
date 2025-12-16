<?php

namespace App\Models;

use Core\Model;

class Menu extends Model
{
    protected $table = 'app_menus';

    public function getMenusByRole($roleId)
    {
        $roleId = (int) $roleId;

        $isMahasiswaRole = ($roleId === 3);

        $whereCondition = '';

        if ($isMahasiswaRole) {
            $whereCondition = ' WHERE m.is_for_mahasiswa = TRUE';
        } else {
            $whereCondition = ' WHERE m.is_for_mahasiswa = FALSE';
        }

        $query = "SELECT 
                    m.id, 
                    m.parent_id, 
                    m.menu_name AS title, 
                    m.route AS link, 
                    m.icon, 
                    m.is_active,
                    m.sort_order,
                    rm.permissions,
                    m.is_for_mahasiswa
                  FROM {$this->table} m
                  LEFT JOIN role_menus rm ON m.id = rm.menu_id AND rm.role_id = :role_id
                  " . $whereCondition . "
                  ORDER BY m.sort_order ASC";

        $this->db->query($query);
        $this->db->bind(':role_id', $roleId);
        $menus = $this->db->fetchAll();
        $menusArray = json_decode(json_encode($menus), true);

        $fullTree = $this->buildTree($menusArray);

        $filteredTree = $this->filterMenuTree($fullTree);

        return $this->cleanOrphanHeaders($filteredTree);
    }

    private function filterMenuTree(array $menus)
    {
        $filtered = [];

        foreach ($menus as $menu) {
            $perms = !empty($menu['permissions']) ? json_decode($menu['permissions'], true) : [];
            if (!is_array($perms)) $perms = [];

            $hasRead  = in_array('read', $perms);
            $isHeader = ($menu['is_active'] == false);

            $hasVisibleChildren = false;
            if (!empty($menu['children'])) {
                $menu['children'] = $this->filterMenuTree($menu['children']);
                if (!empty($menu['children'])) {
                    $hasVisibleChildren = true;
                }
            }

            if ($hasRead || $isHeader || $hasVisibleChildren) {
                $filtered[] = $menu;
            }
        }

        return $filtered;
    }
    private function cleanOrphanHeaders(array $menus)
    {
        $cleaned = [];
        $pendingHeader = null;

        foreach ($menus as $menu) {
            $isHeader = ($menu['is_active'] == false);

            if ($isHeader) {
                $pendingHeader = $menu;
            } else {
                if ($pendingHeader !== null) {
                    $cleaned[] = $pendingHeader;
                    $pendingHeader = null;
                }
                $cleaned[] = $menu;
            }
        }
        return $cleaned;
    }

    private function buildTree(array $elements, $parentId = null)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
