<?php

namespace App\Controllers;

use App\Models\About;
use Core\Controller;

class AboutUsController extends Controller
{
    protected $aboutUsModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->aboutUsModel = new About();
    }

    public function index()
    {
        $data = [
            'title' => 'About Us',
        ];

        view_with_layout('admin/aboutus/index', $data);
    }

    public function data()
    {
        try {
            $aboutus = $this->aboutUsModel->getAll();

            if (!$aboutus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $aboutus
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
                'title'         => request('title'),
                'description'   => request('description'),
                'vision'        => request('vision'),
                'mission'       => request('mission'),
            ];

            $this->aboutUsModel->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan data about us',
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
        $aboutus = $this->aboutUsModel->findBy('id', $id);

        if (!$aboutus) {
            redirect(base_url('admin/aboutus'));
        }

        $data = [
            'title' => 'Edit About Us',
            'about' => $aboutus
        ];

        view_with_layout('admin/aboutus/edit', $data);
    }


    // public function edit($id)
    // {
    //     try {
    //         $aboutus = $this->aboutUsModel->findBy('id', $id);

    //         if (!$aboutus) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ], 404);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Success',
    //             'data' => $aboutus
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan pada server, coba lagi.'
    //         ], 500);
    //     }
    // }

    public function update($id)
    {
        $aboutus = $this->aboutUsModel->find($id);

        if(!$aboutus) {
            redirect(base_url('admin/aboutus'));
        }

        $data = [
            'title' => request('title'),
            'description' => request('description'),
            'vision' => request('vision'),
            'mission' => request('mission'),
        ];

        $this->aboutUsModel->update($id, $data);

        $_SESSION['success_message'] = 'Data About Us berhasil diperbarui.';

        redirect(base_url('admin/aboutus'));

    }


    // public function update($id)
    // {
    //     try {
    //         $aboutus = $this->aboutUsModel->find($id);

    //         if (!$aboutus) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Data About Us tidak ditemukan.'
    //             ], 404);
    //         }

    //         $data = [
    //             'title'         => request('title'),
    //             'description'   => request('description'),
    //             'vision'        => request('vision'),
    //             'mission'       => request('mission'),
    //         ];

    //         $this->aboutUsModel->update($id, $data);


    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Success memperbarui data About Us',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
}
