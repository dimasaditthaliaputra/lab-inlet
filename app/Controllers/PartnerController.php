<?php

namespace App\Controllers;

use App\Models\Partner;
use Core\Controller;

class PartnerController extends Controller
{
    protected $partnerModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->partnerModel = new Partner();
    }

    public function index()
    {
        $data = [
            'title' => 'Partner',
        ];

        view_with_layout('admin/partner/index', $data);
    }

    public function data()
    {
        try {
            $partner = $this->partnerModel->getAll();
            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'partner_name' => $item->partner_name,
                    'partner_logo' => asset( 'assets/uploads/partner_logo/') . $item->partner_logo,
                    'url' => $item->url,
                ];
            }, $partner);

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

    public function store()
    {
        try {
            $form = request('partner_name');

            $role = $this->partnerModel->create([
                '_name' => $form
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan role baru',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $partner = $this->partnerModel->findBy('id', $id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $partner
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
            $partner = $this->partnerModel->find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $form = request('role_name');

            $this->partnerModel->update($id, [
                'role_name' => $form
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data role',
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
            $partner = $this->partnerModel->find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data role tidak ditemukan.'
                ], 404);
            }

            $this->partnerModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data role',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
