<?php

namespace App\Controllers;

use App\Models\Permissions;
use App\Models\SiteSettings;
use Core\Controller;

class SiteSettingsController extends Controller
{
    protected $model;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->model = new SiteSettings();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/site-settings');

        $data = [
            'title' => 'Site Settings',
            'data' => $this->model->getAll(),
            'access' => $access
        ];

        view_with_layout('admin/site_settings/index', $data);
    }

    public function store()
    {
        try {
            $validation = validate([
                'site_name' => [
                    'required' => true,
                    'messages' => ['required' => 'Site name is required']
                ],
                'email' => [
                    'required' => true,
                    'messages' => ['required' => 'Email is required']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $siteSettings = $this->model->getAll();
            $oldData = $siteSettings ?: null;

            $data = [
                'site_name'     => request('site_name'),
                'email'         => request('email'),
                'phone'         => request('phone'),
                'address'       => request('address'),
                'map_embed_url' => request('map_embed_url'),
                'social_links'   => json_encode([
                    'facebook'  => request('facebook'),
                    'instagram' => request('instagram'),
                    'youtube'   => request('youtube'),
                ], JSON_UNESCAPED_SLASHES)
            ];

            $uploadPath = 'uploads/settings/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if (!empty($_FILES['logo_path']['name'])) {

                if ($_FILES['logo_path']['size'] > 2 * 1024 * 1024) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum image size is 2MB'
                    ], 422);
                }

                $ext = strtolower(pathinfo($_FILES['logo_path']['name'], PATHINFO_EXTENSION));
                $newName = md5(time()) . '.' . $ext;

                if (move_uploaded_file($_FILES['logo_path']['tmp_name'], $uploadPath . $newName)) {
                    if ($oldData && !empty($oldData->logo_path) && file_exists($uploadPath . $oldData->logo_path)) {
                        unlink($uploadPath . $oldData->logo_path);
                    }
                    $data['logo_path'] = $newName;
                }
            }

            if ($oldData) {
                $this->model->update($oldData->id, $data);

                logActivity(
                    "Update",
                    "Site settings updated",
                    "settings",
                    $oldData->id, 
                    (array)$oldData,
                    $data
                );
            } else {
                $insertId = $this->model->create($data);

                logActivity(
                    "Create",
                    "Site settings created",
                    "settings",
                    $insertId,
                    null,
                    $data
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Site Settings berhasil disimpan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
