<?php

namespace App\Controllers;

use App\Models\About;
use App\Models\AboutImages;
use Core\Controller;

class AboutUsController extends Controller
{
    protected $aboutUsModel;
    protected $aboutImagesModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->aboutUsModel = new About();
        $this->aboutImagesModel = new AboutImages();
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
            $rows = $this->aboutUsModel->getAll();
            if (!$rows) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $about = [
                'id'          => $rows[0]->id_about,
                'title'       => $rows[0]->title,
                'description' => $rows[0]->description,
                'vision'      => $rows[0]->vision,
                'mision'      => $rows[0]->mission,
                'images'      => []
            ];

            foreach ($rows as $row) {
                $about['images'][] = [
                    'id'         => $row->id_image,
                    'image_name' => asset('uploads/aboutus/') . $row->image_name
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => [$about]
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
        $rows = $this->aboutUsModel->getById($id);

        if (!$rows) {
            redirect(base_url('admin/aboutus'));
        }

        $about = [
            'id'          => $rows[0]->id_about,
            'title'       => $rows[0]->title,
            'description' => $rows[0]->description,
            'vision'      => $rows[0]->vision,
            'mission'     => $rows[0]->mission,
            'images'      => []
        ];

        foreach ($rows as $row) {
            $about['images'][] = [
                'id'         => $row->id_image,
                'image_name' => asset('uploads/aboutus/') . $row->image_name
            ];
        }

        $data = [
            'title' => 'Edit About Us',
            'about' => $about
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

        if (!$aboutus) {
            redirect(base_url('admin/aboutus'));
        }

        $validation = validate([
            'title' => [
                'required' => true,
                'messages' => ['required' => 'Title is required']
            ],
            'description' => [
                'required' => true,
                'messages' => ['required' => 'Description is required']
            ],
            'vision' => [
                'required' => true,
                'messages' => ['required' => 'Vision is required']
            ],
            'mission' => [
                'required' => true,
                'messages' => ['required' => 'Mission is required']
            ]
        ]);

        if (!$validation['success']) {
            $_SESSION['error_message'] = 'Validasi gagal: ' . implode(', ', $validation['errors']);
            redirect(base_url('admin/aboutus/' . $id . '/edit'));
            exit;
        }

        $dataUpdate = [
            'title'       => $validation['data']['title'],
            'description' => $validation['data']['description'],
            'vision'      => $validation['data']['vision'],
            'mission'     => $validation['data']['mission'],
        ];
        $this->aboutUsModel->update($id, $dataUpdate);

        $uploadPath = 'uploads/aboutus/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (isset($_FILES['update_images'])) {
            foreach ($_FILES['update_images']['name'] as $imgId => $fileName) {
                if ($_FILES['update_images']['error'][$imgId] === UPLOAD_ERR_OK) {

                    if ($_FILES['update_images']['size'][$imgId] > $maxSize) {
                        $_SESSION['error_message'] = "Gagal update: File terlalu besar (Max 2MB).";
                        continue;
                    }

                    $tmpName = $_FILES['update_images']['tmp_name'][$imgId];
                    $ext     = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newName = md5(time() . $fileName . uniqid()) . '.' . $ext;

                    if (move_uploaded_file($tmpName, $uploadPath . $newName)) {
                        $oldImg = $this->aboutImagesModel->find($imgId);
                        if ($oldImg) {
                            $oldFile = $uploadPath . $oldImg->image_name;
                            if (file_exists($oldFile)) {
                                unlink($oldFile);
                            }

                            $this->aboutImagesModel->update($imgId, ['image_name' => $newName]);
                        }
                    }
                }
            }
        }

        if (isset($_FILES['new_images'])) {
            $countNew = count($_FILES['new_images']['name']);

            for ($i = 0; $i < $countNew; $i++) {
                if ($_FILES['new_images']['error'][$i] === UPLOAD_ERR_OK) {

                    if ($_FILES['new_images']['size'][$i] > $maxSize) {
                        continue;
                    }

                    $tmpName = $_FILES['new_images']['tmp_name'][$i];
                    $fileName = $_FILES['new_images']['name'][$i];
                    $ext     = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newName = md5(time() . $fileName . uniqid()) . '.' . $ext;

                    if (move_uploaded_file($tmpName, $uploadPath . $newName)) {
                        $imageData = [
                            'aboutus_id' => $id,
                            'image_name' => $newName
                        ];
                        $this->aboutImagesModel->create($imageData);
                    }
                }
            }
        }

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
