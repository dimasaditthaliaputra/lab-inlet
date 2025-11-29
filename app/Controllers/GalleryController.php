<?php

namespace App\Controllers;

use App\Models\Gallery;
use Core\Controller;

class GalleryController extends Controller
{
    protected $galleryModel;
    protected $uploadDir;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->galleryModel = new Gallery();

        // Folder upload image
        $this->uploadDir = __DIR__ . '/../../public/assets/images/gallery/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /* ============================================================
     *                       IMAGE  (type = 'Photo')
     * ============================================================
     */

    public function imageIndex()
    {
        $data = [
            'title'     => 'Gallery Image',
            'buttonSts' => true,
        ];

        view_with_layout('admin/gallery/image/index', $data);
    }

    public function imageData()
    {
        try {
            // DI DB: type = 'Photo'
            $items = $this->galleryModel->getByType('Photo');

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle upload image file
     */
    protected function handleUpload($file, $oldFileName = null)
    {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldFileName;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Gagal meng-upload file gambar.');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            throw new \Exception('Format gambar harus jpg, jpeg, png, atau webp.');
        }

        $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $this->uploadDir . $newName)) {
            throw new \Exception('Gagal memindahkan file upload.');
        }

        // hapus file lama jika ada
        if ($oldFileName && file_exists($this->uploadDir . $oldFileName)) {
            @unlink($this->uploadDir . $oldFileName);
        }

        return $newName;
    }

    public function imageStore()
    {
        try {
            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Title is required',
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image is required',
                    'errors'  => ['image' => 'Image is required']
                ], 422);
            }

            $imageName = $this->handleUpload($_FILES['image']);

            $data = [
                'title'       => $validation['data']['title'],
                'description' => $_POST['description'] ?? null,
                'image_name'  => $imageName,
                'url'         => null,
                // Wajib 'Photo' supaya lolos constraint gallery_type_check
                'type'        => 'Photo',
                'upload_date' => date('Y-m-d H:i:s'),
            ];

            $insertId = $this->galleryModel->create($data);
            $item     = $this->galleryModel->find($insertId);

            logActivity(
                "Create",
                "Gallery image {$item->title} successfully created",
                "gallery",
                $item->id,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan gallery image',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function imageEdit($id)
    {
        try {
            $item = $this->galleryModel->findBy('id', $id);

            // Cek juga type = 'Photo'
            if (!$item || $item->type !== 'Photo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data image tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    public function imageUpdate($id)
    {
        try {
            $oldData = $this->galleryModel->find($id);

            if (!$oldData || $oldData->type !== 'Photo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gallery image tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Title is required',
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $newImageName = $oldData->image_name;

            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $newImageName = $this->handleUpload($_FILES['image'], $oldData->image_name);
            }

            $newData = [
                'title'       => $validation['data']['title'],
                'description' => $_POST['description'] ?? null,
                'image_name'  => $newImageName,
                'url'         => null,
                'type'        => 'Photo',
            ];

            $this->galleryModel->update($id, $newData);

            logActivity(
                "Update",
                "Gallery image {$validation['data']['title']} successfully updated",
                "gallery",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data gallery image',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function imageDestroy($id)
    {
        try {
            $item = $this->galleryModel->find($id);

            if (!$item || $item->type !== 'Photo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gallery image tidak ditemukan.'
                ], 404);
            }

            if ($item->image_name && file_exists($this->uploadDir . $item->image_name)) {
                @unlink($this->uploadDir . $item->image_name);
            }

            $this->galleryModel->delete($id);

            logActivity(
                "Delete",
                "Gallery image {$item->title} successfully deleted",
                "gallery",
                $id,
                $item,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data gallery image',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ============================================================
     *                       VIDEO (type = 'Video')
     * ============================================================
     */

    public function videoIndex()
    {
        $data = [
            'title'     => 'Gallery Video',
            'buttonSts' => true,
        ];

        view_with_layout('admin/gallery/video/index', $data);
    }

    public function videoData()
    {
        try {
            // DI DB: type = 'Video'
            $items = $this->galleryModel->getByType('Video');

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function videoStore()
    {
        try {
            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Title is required',
                    ]
                ],
                'url' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'URL is required',
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $data = [
                'title'       => $validation['data']['title'],
                'description' => $_POST['description'] ?? null,
                'image_name'  => null,
                'url'         => $validation['data']['url'],
                // Wajib 'Video' supaya cocok constraint
                'type'        => 'Video',
                'upload_date' => date('Y-m-d H:i:s'),
            ];

            $insertId = $this->galleryModel->create($data);
            $item     = $this->galleryModel->find($insertId);

            logActivity(
                "Create",
                "Gallery video {$item->title} successfully created",
                "gallery",
                $item->id,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan gallery video',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function videoEdit($id)
    {
        try {
            $item = $this->galleryModel->findBy('id', $id);

            if (!$item || $item->type !== 'Video') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data video tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    public function videoUpdate($id)
    {
        try {
            $oldData = $this->galleryModel->find($id);

            if (!$oldData || $oldData->type !== 'Video') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gallery video tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'Title is required',
                    ]
                ],
                'url' => [
                    'required' => true,
                    'messages' => [
                        'required' => 'URL is required',
                    ]
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $newData = [
                'title'       => $validation['data']['title'],
                'description' => $_POST['description'] ?? null,
                'image_name'  => null,
                'url'         => $validation['data']['url'],
                'type'        => 'Video',
            ];

            $this->galleryModel->update($id, $newData);

            logActivity(
                "Update",
                "Gallery video {$validation['data']['title']} successfully updated",
                "gallery",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data gallery video',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function videoDestroy($id)
    {
        try {
            $item = $this->galleryModel->find($id);

            if (!$item || $item->type !== 'Video') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gallery video tidak ditemukan.'
                ], 404);
            }

            $this->galleryModel->delete($id);

            logActivity(
                "Delete",
                "Gallery video {$item->title} successfully deleted",
                "gallery",
                $id,
                $item,
                null
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data gallery video',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
