<?php

namespace App\Controllers;

use App\Models\Team;
use Core\Controller;

class TeamController extends Controller
{
    protected $teamModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->teamModel = new Team();
    }

    public function index()
    {
        $data = [
            'title' => 'Team',
        ];

        view_with_layout('admin/team/index', $data);
    }

    public function data()
    {
        try {
            $team = $this->teamModel->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $team
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store()
    {
        try {
            $data = [
                'name'          => request('name'),
                'position'      => request('position'),
                'nip'           => request('nip'),
                'nidn'          => request('nidn'),
                'study_program' => request('study_program'),
                'description'   => request('description'),
                'social_media'  => request('social_media'),
            ];

            $this->teamModel->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan team baru',
            ], 200);
        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Terjadi kesalahan pada server, coba lagi.'
            // ], 500);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error'   => $e->getMessage() // opsional, bisa dihapus saat production
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $team = $this->teamModel->findBy('id', $id);

            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $team
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    public function update($id)
    {
        try {
            $team = $this->teamModel->find($id);

            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data team tidak ditemukan.'
                ], 404);
            }

            $form = [
                'name'          => request('name'),
                'position'      => request('position'),
                'nip'           => request('nip'),
                'nidn'          => request('nidn'),
                'study_program' => request('study_program'),
                'description'   => request('description'),
                'social_media'  => request('social_media'),
            ];

            $this->teamModel->update($id, $form);

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data Team',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $team = $this->teamModel->find($id);

            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data team tidak ditemukan.'
                ], 404);
            }

            $this->teamModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data team',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
