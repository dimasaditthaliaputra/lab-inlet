<?php

namespace App\Controllers;

use App\Models\LogActivity;
use Core\Controller;

use function PHPSTORM_META\map;

class LogActivityController extends Controller
{
    protected $logModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->logModel = new LogActivity();
    }

    public function index()
    {
        $data = [
            'title' => 'Log Activity User',
        ];

        view_with_layout('admin/log_activity/index', $data);
    }

    public function data()
    {
        try {
            $roles = $this->logModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'username' => $item->username,
                    'action_type' => $item->action_type,
                    'table_name' => $item->table_name,
                    'record_id' => $item->record_id,
                    'description' => $item->description,
                    'old_data' => $item->old_data ? json_decode($item->old_data, true) : null,
                    'new_data' => $item->new_data ? json_decode($item->new_data, true) : null,
                    'created_at' => date('d M Y H:i:s', strtotime($item->created_at))
                ];
            }, $roles);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
