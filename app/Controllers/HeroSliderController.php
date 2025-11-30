<?php

namespace App\Controllers;

use App\Models\HeroSlider;
use Core\Controller;

class HeroSliderController extends Controller
{
    protected $heroModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->heroModel = new HeroSlider();
    }

    /* ========== INDEX & DATA ========== */

    public function index()
    {
        $data = [
            'title' => 'Hero Slider',
        ];

        view_with_layout('admin/hero_slider/index', $data);
    }

    public function data()
    {
        try {
            $sliders = $this->heroModel->getAll();

            $data = array_map(function ($item) {
                $isActive = filter_var($item->is_active, FILTER_VALIDATE_BOOLEAN);

                return [
                    'id'          => $item->id,
                    'title'       => $item->title,
                    'subtitle'    => $item->subtitle,
                    'image'       => $item->image_name ? asset('uploads/hero_slider/') . $item->image_name : null,
                    'image_name'  => $item->image_name,
                    'button_text' => $item->button_text,
                    'button_url'  => $item->button_url,
                    'sort_order'  => $item->sort_order,
                    'is_active'   => $isActive,
                    'created_at'  => $item->created_at,
                ];
            }, $sliders);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ========== HELPER UPLOAD ========== */

    protected function uploadImage($oldImageName = null)
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            // kalau tidak ada file baru, pakai gambar lama
            return $oldImageName;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Gagal meng-upload file gambar.');
        }

        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($_FILES['image']['size'] > $maxSize) {
            throw new \Exception('Maximum file size is 2MB');
        }

        $fileTmpPath   = $_FILES['image']['tmp_name'];
        $fileName      = $_FILES['image']['name'];
        $fileNameCmps  = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedExt)) {
            throw new \Exception('Format gambar harus jpg, jpeg, png, atau webp.');
        }

        $newFileName   = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = 'uploads/hero_slider/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
            // hapus gambar lama kalau ada
            if ($oldImageName) {
                $oldPath = __DIR__ . '/../../public/uploads/hero_slider/' . $oldImageName;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            return $newFileName;
        }

        throw new \Exception('Gagal memindahkan file upload.');
    }

    protected function parseIsActive()
    {
        // checkbox "is_active" (value="1") → bool
        return isset($_POST['is_active']) && $_POST['is_active'] == '1';
    }

    protected function parseSortOrder()
    {
        return (isset($_POST['sort_order']) && $_POST['sort_order'] !== '')
            ? (int) $_POST['sort_order']
            : 0;
    }

    /* ========== STORE ========== */

    public function store()
    {
        try {
            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'Title wajib diisi']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            // gambar WAJIB diisi untuk create
            try {
                $imageName = $this->uploadImage();
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => ['image' => $e->getMessage()]
                ], 422);
            }

            $data = [
                'title'       => $validation['data']['title'],
                'subtitle'    => $_POST['subtitle']    ?? null,
                'image_name'  => $imageName,
                'button_text' => $_POST['button_text'] ?? null,
                'button_url'  => $_POST['button_url']  ?? null,
                'sort_order'  => $this->parseSortOrder(),
                'is_active'   => $this->parseIsActive(),
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            $insertId = $this->heroModel->create($data);

            logActivity(
                "Create",
                "Hero slider '{$data['title']}' successfully created",
                "hero_slider",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan hero slider baru',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    /* ========== EDIT ========== */

    public function edit($id)
    {
        try {
            $slider = $this->heroModel->findBy('id', $id);

            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'id'          => $slider->id,
                'title'       => $slider->title,
                'subtitle'    => $slider->subtitle,
                'button_text' => $slider->button_text,
                'button_url'  => $slider->button_url,
                'sort_order'  => $slider->sort_order,
                'is_active'   => filter_var($slider->is_active, FILTER_VALIDATE_BOOLEAN),
                'image_url'   => $slider->image_name ? asset('uploads/hero_slider/') . $slider->image_name : null,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    /* ========== UPDATE ========== */

    public function update($id)
    {
        try {
            $oldData = $this->heroModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data hero slider tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'Title wajib diisi']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $imageName = $oldData->image_name;

            try {
                // kalau user upload file baru → replace
                $imageName = $this->uploadImage($oldData->image_name);
            } catch (\Exception $e) {
                if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors'  => ['image' => $e->getMessage()]
                    ], 422);
                }
            }

            $newData = [
                'title'       => $validation['data']['title'],
                'subtitle'    => $_POST['subtitle']    ?? null,
                'image_name'  => $imageName,
                'button_text' => $_POST['button_text'] ?? null,
                'button_url'  => $_POST['button_url']  ?? null,
                'sort_order'  => $this->parseSortOrder(),
                'is_active'   => $this->parseIsActive(),
            ];

            $this->heroModel->update($id, $newData);

            logActivity(
                "Update",
                "Hero slider '{$validation['data']['title']}' successfully updated",
                "hero_slider",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data hero slider',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ========== DESTROY ========== */

    public function destroy($id)
    {
        try {
            $slider = $this->heroModel->find($id);

            if (!$slider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data hero slider tidak ditemukan.'
                ], 404);
            }

            if ($slider->image_name) {
                $path = __DIR__ . '/../../public/uploads/hero_slider/' . $slider->image_name;
                if (file_exists($path)) {
                    @unlink($path);
                }
            }

            logActivity(
                "Delete",
                "Hero slider {$slider->title} successfully deleted",
                "hero_slider",
                $id,
                $slider,
                null
            );

            $this->heroModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data hero slider',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
