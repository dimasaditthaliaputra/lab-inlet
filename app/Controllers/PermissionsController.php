<?php

namespace App\Controllers;

use App\Models\Permissions;
use Core\Controller;

class PermissionsController extends Controller
{
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $listRoles = $this->permissionsModel->getRoles();
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/permissions');

        $data = [
            'title' => 'Permissions Management',
            'listRoles' => $listRoles,
            'access' => $access
        ];

        view_with_layout('admin/permissions/index', $data);
    }

    public function data($roleId = null)
    {
        if (empty($roleId)) {
            return response()->json([
                'success' => false,
                'message' => 'Role ID is required.',
                'data' => []
            ], 400);
        }

        try {
            $permissions = $this->permissionsModel->getMenuWithPermissions($roleId);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        if (!$input || empty($input['role_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided.'
            ], 400);
        }

        $roleId = $input['role_id'];
        $permissions = $input['permissions'] ?? [];

        try {
            $this->permissionsModel->updatePermissions($roleId, $permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions saved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePermissions()
    {
        $roleId = 1;

        if (empty($roleId)) {
            return response()->json([
                'success' => false,
                'message' => 'Role ID is required.',
                'data' => []
            ], 400);
        }

        try {
            $permissions = $this->permissionsModel->generatePermissions($roleId);

            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate permissions.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
