<?php

namespace App\Controllers;

use App\Models\ProjectLab;
use App\Models\KategoriProject;
use App\Models\Permissions;
use Core\Controller;

class ProjectLabController extends Controller
{
    protected $projectModel;
    protected $kategoriModel;
    protected $permissionsModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->projectModel = new ProjectLab();
        $this->kategoriModel = new KategoriProject();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/project-lab');

        $data = [
            'title' => 'Project Lab',
            'categories' => $this->kategoriModel->getAll(),
            'access' => $access
        ];

        view_with_layout('admin/project_lab/index', $data);
    }

    public function data()
    {
        try {
            $projects = $this->projectModel->getAll();
            $data = array_map(function ($item) {
                $imageRaw = $item->image_url;
                $imageList = [];

                if ($imageRaw && strpos($imageRaw, '{') === 0) {
                    $cleaned = trim($imageRaw, '{}');

                    if (!empty($cleaned)) {
                        $images = explode(',', $cleaned);

                        foreach ($images as $img) {
                            $cleanImg = trim($img, '"');
                            $imageList[] = asset('uploads/project_images/') . $cleanImg;
                        }
                    }
                } elseif ($imageRaw) {
                    $imageList[] = asset('uploads/project_images/') . $imageRaw;
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'kategori_name' => $item->kategori_name,
                    'video_url' => $item->video_url,
                    'images_list' => $imageList,
                    'thumbnail' => !empty($imageList) ? $imageList[0] : null,
                    'status' => $item->status,
                    'created_at' => $item->created_at
                ];
            }, $projects);

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
            $validation = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama project wajib diisi']
                ],
                'description' => [
                    'required' => true,
                    'messages' => ['required' => 'Deskripsi wajib diisi']
                ],
                'status' => [
                    'required' => true,
                    'messages' => ['required' => 'Status wajib dipilih']
                ],
                'image_url' => [
                    'required' => false
                ]
            ]);

            if (!isset($_POST['id_kategori']) || !is_array($_POST['id_kategori'])) {
                return response()->json(['success' => false, 'message' => 'Minimal satu kategori wajib dipilih'], 422);
            }

            if (!$validation['success']) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validation['errors']], 422);
            }

            $uploadedFiles = [];
            $uploadFileDir = 'uploads/project_images/';

            if (isset($_FILES['image_url']) && is_array($_FILES['image_url']['name'])) {
                $maxSize = 2 * 1024 * 1024;
                $count = count($_FILES['image_url']['name']);

                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['image_url']['size'][$i] > $maxSize) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Salah satu ukuran gambar lebih dari 2MB',
                            'errors'  => [
                                'image' => 'Maximum file size is 2MB'
                            ]
                        ], 422);
                    }
                }

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['image_url']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['image_url']['tmp_name'][$i];
                        $fileName    = $_FILES['image_url']['name'][$i];
                        $fileNameCmps = explode(".", $fileName);
                        $fileExtension = strtolower(end($fileNameCmps));

                        $newFileName = md5(time() . $fileName . $i) . '.' . $fileExtension;

                        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                            $uploadedFiles[] = $newFileName;
                        }
                    }
                }
            }

            $formattedImage = !empty($uploadedFiles) ? "{" . implode(',', $uploadedFiles) . "}" : "{}";

            $data = [
                'name' => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'status' => $validation['data']['status'],
                'video_url' => $_POST['video_url'] ?? null,
                'image_url' => $formattedImage,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $insertId = $this->projectModel->create($data);

            $this->projectModel->assignCategories($insertId, $_POST['id_kategori']);

            logActivity(
                "Create",
                "Project '{$data['name']}' successfully created",
                "project_lab",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan project baru',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $project = $this->projectModel->getProjectWithCategories($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $project
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
            $project = $this->projectModel->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data project tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Nama project wajib diisi']
                ],
                'description' => [
                    'required' => true,
                    'messages' => ['required' => 'Deskripsi wajib diisi']
                ],
                'status' => [
                    'required' => true,
                    'messages' => ['required' => 'Status wajib dipilih']
                ],
                'image_url' => [
                    'required' => false
                ]
            ]);

            if (!isset($_POST['id_kategori']) || !is_array($_POST['id_kategori'])) {
                return response()->json(['success' => false, 'message' => 'Minimal satu kategori wajib dipilih'], 422);
            }

            if (!$validation['success']) return response()->json(['success' => false, 'errors' => $validation['errors']], 422);

            $uploadedFiles = [];
            $uploadFileDir = 'uploads/project_images/';

            if (isset($_FILES['image_url']) && is_array($_FILES['image_url']['name']) && $_FILES['image_url']['error'][0] === UPLOAD_ERR_OK) {

                $maxSize = 2 * 1024 * 1024;
                $count = count($_FILES['image_url']['name']);

                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['image_url']['size'][$i] > $maxSize) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Salah satu ukuran gambar lebih dari 2MB',
                            'errors'  => [
                                'image' => 'Maximum file size is 2MB'
                            ]
                        ], 422);
                    }
                }

                if (!empty($project->image_url)) {
                    $oldImageStr = trim($project->image_url, '{}');
                    if (!empty($oldImageStr)) {
                        $oldImages = explode(',', $oldImageStr);
                        foreach ($oldImages as $oldImg) {
                            $cleanOldImg = trim($oldImg, '"');
                            $oldFilePath = __DIR__ . '/../../public/uploads/project_images/' . $cleanOldImg;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                    }
                }

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['image_url']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['image_url']['tmp_name'][$i];
                        $fileName    = $_FILES['image_url']['name'][$i];
                        $fileNameCmps = explode(".", $fileName);
                        $fileExtension = strtolower(end($fileNameCmps));

                        $newFileName = md5(time() . $fileName . $i) . '.' . $fileExtension;

                        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                            $uploadedFiles[] = $newFileName;
                        }
                    }
                }
            }

            $data = [
                'name' => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'status' => $validation['data']['status'],
                'video_url' => $_POST['video_url'] ?? null,
            ];

            if (!empty($uploadedFiles)) {
                $data['image_url'] = "{" . implode(',', $uploadedFiles) . "}";
            }

            $this->projectModel->update($id, $data);

            $this->projectModel->assignCategories($id, $_POST['id_kategori']);

            logActivity(
                "Update",
                "Project '{$data['name']}' successfully updated",
                "project_lab",
                $id,
                $project,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data project',
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
            $project = $this->projectModel->find($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data project tidak ditemukan.'
                ], 404);
            }

            if (!empty($project->image_url)) {
                $imageStr = trim($project->image_url, '{}');

                if (!empty($imageStr)) {
                    $images = explode(',', $imageStr);

                    foreach ($images as $img) {
                        $cleanImg = trim($img, '"');
                        $path = __DIR__ . '/../../public/uploads/project_images/' . $cleanImg;

                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                }
            }

            logActivity(
                "Delete",
                "Project {$project->name} successfully deleted",
                "project_lab",
                $id,
                $project,
                null
            );

            $this->projectModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data project',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
