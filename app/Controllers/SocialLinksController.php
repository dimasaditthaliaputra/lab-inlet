<?php

namespace App\Controllers;

use App\Models\SocialLinks;
use Core\Controller;

class SocialLinksController extends Controller
{
    protected $socialModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }
        $this->socialModel = new SocialLinks();
    }

    public function index()
    {
        $data = [
            'title' => 'Social Media Project',
        ];
        view_with_layout('admin/social_links/index', $data);
    }

    public function data()
    {
        try {
            $links = $this->socialModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon_name' => $item->icon_name,
                    'image_cover' => $item->image_cover
                        ? asset('uploads/social_links/' . $item->image_cover)
                        : null,
                ];
            }, $links);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    protected function uploadImage($oldImage = null)
    {
        if (!isset($_FILES['image_cover']) || $_FILES['image_cover']['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldImage;
        }

        if ($_FILES['image_cover']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Gagal upload file');
        }

        $ext = strtolower(pathinfo($_FILES['image_cover']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            throw new \Exception('Format harus jpg/jpeg/png/webp');
        }

        $newFile = md5(time()) . '.' . $ext;
        $dir = 'uploads/social_links/';

        if (!is_dir($dir)) mkdir($dir, 0777, true);

        if (move_uploaded_file($_FILES['image_cover']['tmp_name'], $dir . $newFile)) {
            if ($oldImage && file_exists($dir . $oldImage)) {
                unlink($dir . $oldImage);
            }
            return $newFile;
        }

        throw new \Exception('Upload gagal');
    }

    public function store()
    {
        try {
            $valid = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama wajib diisi']
                ]
            ]);

            if (!$valid['success']) {
                return response()->json(['success' => false, 'errors' => $valid['errors']], 422);
            }

            $data = [
                'name' => $valid['data']['name'],
                'icon_name' => $_POST['icon_name'] ?? null,
            ];

            if (empty($data['icon_name'])) {
                $data['image_cover'] = $this->uploadImage();
            }

            $this->socialModel->create($data);

            return response()->json(['success' => true, 'message' => 'Berhasil menambah data']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function edit($id)
    {
        $item = $this->socialModel->find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $item->image_cover = $item->image_cover
            ? asset('uploads/social_links/' . $item->image_cover)
            : null;

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update($id)
    {
        try {
            $item = $this->socialModel->find($id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            $valid = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama wajib diisi']
                ]
            ]);

            if (!$valid['success']) {
                return response()->json(['success' => false, 'errors' => $valid['errors']], 422);
            }

            $data = [
                'name' => $valid['data']['name'],
                'icon_name' => $_POST['icon_name'] ?? null,
            ];

            if (empty($data['icon_name'])) {
                $data['image_cover'] = $this->uploadImage($item->image_cover);
            }

            $this->socialModel->update($id, $data);

            return response()->json(['success' => true, 'message' => 'Berhasil mengupdate data']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->socialModel->find($id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Tidak ditemukan'], 404);
            }

            if ($item->image_cover && file_exists('uploads/social_links/' . $item->image_cover)) {
                unlink('uploads/social_links/' . $item->image_cover);
            }

            $this->socialModel->delete($id);

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    private function serverError(\Exception $e)
    {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
