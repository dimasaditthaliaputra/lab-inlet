<?php

namespace App\Models;

use Core\Model;

class Permissions extends Model
{
    protected $table = 'role_menus';

    /**
     * Mengambil menu beserta permission-nya.
     * Sebelum mengambil, lakukan sinkronisasi data terlebih dahulu.
     */
    public function getMenuWithPermissions($roleId)
    {
        $roleId = (int) $roleId;

        // 1. SYNC: Pastikan semua menu dari app_menus ada di role_menus untuk role ini
        $this->syncMissingMenus($roleId);

        // 2. FETCH: Ambil datanya
        $sql = "SELECT 
                    m.id, 
                    m.menu_name, 
                    m.route, 
                    m.icon, 
                    m.parent_id, 
                    m.sort_order,
                    m.permissions as available_permissions, -- Permission bawaan menu (Manifest)
                    rm.permissions as current_permissions   -- Permission yang aktif untuk role ini
                FROM app_menus m
                LEFT JOIN role_menus rm ON m.id = rm.menu_id AND rm.role_id = :role_id
                ORDER BY m.sort_order ASC";

        $this->db->query($sql);
        $this->db->bind(':role_id', $roleId);
        $data = $this->db->fetchAll();

        return $this->sortParentChild($data);
    }

    /**
     * Fungsi untuk insert otomatis data ke role_menus 
     * jika ada menu di app_menus yang belum dimiliki oleh role tersebut.
     */
    public function syncMissingMenus($roleId)
    {
        // Query ini akan insert ke role_menus HANYA jika datanya belum ada (WHERE NOT EXISTS)
        // Default permissions kita set NULL atau '[]' (array kosong JSON)
        $sql = "INSERT INTO role_menus (role_id, menu_id, permissions)
                SELECT :role_id, id, '[]'
                FROM app_menus m
                WHERE NOT EXISTS (
                    SELECT 1 FROM role_menus rm 
                    WHERE rm.menu_id = m.id AND rm.role_id = :role_id
                )";

        $this->db->query($sql);
        $this->db->bind(':role_id', $roleId);
        $this->db->execute();
    }

    public function generatePermissions($roleId)
    {
        try {
            $sql = "INSERT INTO role_menus (role_id, menu_id, permissions)
                SELECT :role_id, id, COALESCE(m.permissions, '[]')
                FROM app_menus m
                WHERE NOT EXISTS (
                    SELECT 1 FROM role_menus rm 
                    WHERE rm.menu_id = m.id AND rm.role_id = :role_id
                )";

            $this->db->query($sql);
            $this->db->bind(':role_id', $roleId);
            $this->db->execute();

            return true;
        } catch (\exception $e) {
            return false;
        }
    }

    /**
     * Update permissions berdasarkan input dari Frontend.
     */
    public function updatePermissions($roleId, $permissionsData)
    {
        try {
            $this->db->query("BEGIN")->execute();

            $resetSql = "UPDATE role_menus SET permissions = '[]' WHERE role_id = :role_id";
            $this->db->query($resetSql);
            $this->db->bind(':role_id', $roleId);
            $this->db->execute();

            $updateSql = "UPDATE role_menus SET permissions = :perms WHERE role_id = :role_id AND menu_id = :menu_id";

            foreach ($permissionsData as $menuId => $permsObj) {
                $permsArray = array_keys(array_filter($permsObj, function ($val) {
                    return $val === true;
                }));

                $jsonPerms = json_encode($permsArray);

                $this->db->query($updateSql);
                $this->db->bind(':perms', $jsonPerms);
                $this->db->bind(':role_id', $roleId);
                $this->db->bind(':menu_id', $menuId);
                $this->db->execute();
            }

            $this->db->query("COMMIT")->execute();
            return true;
        } catch (\Exception $e) {
            $this->db->query("ROLLBACK")->execute();
            throw $e;
        }
    }

    public function getRoles()
    {
        return $this->db->query("SELECT * FROM roles ORDER BY role_name")->fetchAll();
    }

    private function sortParentChild(array $menus)
    {
        $parents = [];
        $children = [];

        foreach ($menus as $menu) {
            $menu = (array) $menu;

            if (empty($menu['parent_id'])) {
                $parents[] = $menu;
            } else {
                $children[$menu['parent_id']][] = $menu;
            }
        }

        $result = [];

        foreach ($parents as $parent) {
            $parent['level'] = 0;
            $result[] = $parent;

            if (isset($children[$parent['id']])) {
                foreach ($children[$parent['id']] as $child) {
                    $child['level'] = 1;
                    $result[] = $child;
                }
            }
        }

        return $result;
    }

    public function getPermissionByRoute($roleId, $route)
    {
        $sql = "SELECT rm.permissions 
                FROM role_menus rm
                JOIN app_menus m ON m.id = rm.menu_id
                WHERE rm.role_id = :role_id 
                AND m.route = :route
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':route', $route);

        $result = $this->db->fetch();

        if ($result && !empty($result->permissions)) {
            return json_decode($result->permissions, true) ?? [];
        }

        return [];
    }
}
