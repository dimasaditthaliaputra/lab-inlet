<?php

namespace App\Controllers;

use App\Models\Permissions;
use App\Models\ResearchFocus;
use Core\Controller;

class ResearchFocusController extends Controller
{
    protected $researchModel;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->researchModel = new ResearchFocus();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/research-focus');

        $data = [
            'title' => 'Research Focus',
            'access' => $access
        ];

        view_with_layout('admin/research_focus/index', $data);
    }

    public function data()
    {
        try {
            $items = $this->researchModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id'          => $item->id,
                    'title'       => $item->title,
                    'description' => $item->description,
                    'icon_name'   => $item->icon_name,
                    'image_cover' => $item->image_cover
                        ? asset('uploads/research_focus/') . $item->image_cover
                        : null,
                    'sort_order'  => $item->sort_order,
                ];
            }, $items);

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

    /* ======== HELPER UPLOAD ======== */

    protected function uploadImage($oldImageName = null)
    {
        if (!isset($_FILES['image_cover']) || $_FILES['image_cover']['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldImageName;
        }

        if ($_FILES['image_cover']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Gagal meng-upload file gambar.');
        }

        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($_FILES['image_cover']['size'] > $maxSize) {
            throw new \Exception('Maximum file size is 2MB');
        }

        $fileTmpPath   = $_FILES['image_cover']['tmp_name'];
        $fileName      = $_FILES['image_cover']['name'];
        $fileNameCmps  = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedExt)) {
            throw new \Exception('Format gambar harus jpg, jpeg, png, atau webp.');
        }

        $newFileName   = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = 'uploads/research_focus/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
            if ($oldImageName) {
                $oldPath = __DIR__ . '/../../public/uploads/research_focus/' . $oldImageName;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            return $newFileName;
        }

        throw new \Exception('Gagal memindahkan file upload.');
    }

    protected function parseSortOrder()
    {
        return (isset($_POST['sort_order']) && $_POST['sort_order'] !== '')
            ? (int) $_POST['sort_order']
            : 0;
    }

    /* ======== STORE ======== */

    public function store()
    {
        try {
            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'Title is required']
                ],
                'description' => [
                    'required' => false,
                ],
                'icon_name' => [
                    'required' => false,
                ],
                'image_cover' => [
                    'required' => false,
                ],
                'sort_order' => [
                    'required' => false,
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $iconName = trim($validation['data']['icon_name'] ?? '');

            $hasFile = isset($_FILES['image_cover']) &&
                       $_FILES['image_cover']['error'] !== UPLOAD_ERR_NO_FILE;

            // Wajib pilih salah satu dan tidak boleh dua-duanya
            if ($iconName === '' && !$hasFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Icon atau Image Cover wajib diisi salah satu',
                    'errors'  => [
                        'icon_name'   => 'Isi icon atau upload image cover',
                        'image_cover' => 'Isi icon atau upload image cover',
                    ]
                ], 422);
            }

            if ($iconName !== '' && $hasFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih salah satu: icon atau image cover, tidak boleh keduanya',
                    'errors'  => [
                        'icon_name'   => 'Tidak boleh bersamaan dengan image cover',
                        'image_cover' => 'Tidak boleh bersamaan dengan icon',
                    ]
                ], 422);
            }

            $imageName = null;
            if ($hasFile) {
                try {
                    $imageName = $this->uploadImage();
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors'  => ['image_cover' => $e->getMessage()]
                    ], 422);
                }
            }

            $data = [
                'title'       => $validation['data']['title'],
                'description' => $validation['data']['description'] ?? null,
                'icon_name'   => $iconName !== '' ? $iconName : null,
                'image_cover' => $imageName,
                'sort_order'  => $this->parseSortOrder(),
            ];

            $insertId = $this->researchModel->create($data);

            logActivity(
                "Create",
                "Research focus '{$data['title']}' successfully created",
                "research_focus",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan research focus baru',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    /* ======== EDIT ======== */

    public function edit($id)
    {
        try {
            $item = $this->researchModel->findBy('id', $id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'id'          => $item->id,
                'title'       => $item->title,
                'description' => $item->description,
                'icon_name'   => $item->icon_name,
                'image_cover' => $item->image_cover
                    ? asset('uploads/research_focus/') . $item->image_cover
                    : null,
                'sort_order'  => $item->sort_order,
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

    /* ======== UPDATE ======== */

    public function update($id)
    {
        try {
            $oldData = $this->researchModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data research focus tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'Title is required']
                ],
                'description' => [
                    'required' => false,
                ],
                'icon_name' => [
                    'required' => false,
                ],
                'image_cover' => [
                    'required' => false,
                ],
                'sort_order' => [
                    'required' => false,
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            $newIcon = trim($validation['data']['icon_name'] ?? '');
            $hasNewFile = isset($_FILES['image_cover']) &&
                          $_FILES['image_cover']['error'] !== UPLOAD_ERR_NO_FILE;

            $imageFileName = $oldData->image_cover;

            // Kalau upload file baru
            if ($hasNewFile) {
                if ($newIcon !== '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilih salah satu: icon atau image cover, tidak boleh keduanya',
                        'errors'  => [
                            'icon_name'   => 'Tidak boleh bersamaan dengan image cover',
                            'image_cover' => 'Tidak boleh bersamaan dengan icon',
                        ]
                    ], 422);
                }

                try {
                    $imageFileName = $this->uploadImage($oldData->image_cover);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors'  => ['image_cover' => $e->getMessage()]
                    ], 422);
                }
            }

            // Kalau user isi icon, kita buang image lama (switch dari image → icon)
            if ($newIcon !== '' && $oldData->image_cover && !$hasNewFile) {
                $oldPath =__DIR__ . '/../../public/uploads/research_focus/' . $oldData->image_cover;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                $imageFileName = null;
            }

            // Pastikan minimal ada icon atau image
            if ($newIcon === '' && !$imageFileName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Icon atau Image Cover wajib diisi salah satu',
                    'errors'  => [
                        'icon_name'   => 'Isi icon atau upload image cover',
                        'image_cover' => 'Isi icon atau upload image cover',
                    ]
                ], 422);
            }

            $newData = [
                'title'       => $validation['data']['title'],
                'description' => $validation['data']['description'] ?? null,
                'icon_name'   => $newIcon !== '' ? $newIcon : null,
                'image_cover' => $imageFileName,
                'sort_order'  => $this->parseSortOrder(),
            ];

            $this->researchModel->update($id, $newData);

            logActivity(
                "Update",
                "Research focus '{$newData['title']}' successfully updated",
                "research_focus",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data research focus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ======== DESTROY ======== */

    public function destroy($id)
    {
        try {
            $item = $this->researchModel->find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data research focus tidak ditemukan.'
                ], 404);
            }

            if (!empty($item->image_cover)) {
                $path = __DIR__ . '/../../public/uploads/research_focus/' . $item->image_cover;
                if (file_exists($path)) {
                    @unlink($path);
                }
            }

            logActivity(
                "Delete",
                "Research focus {$item->title} successfully deleted",
                "research_focus",
                $id,
                $item,
                null
            );

            $this->researchModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data research focus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}