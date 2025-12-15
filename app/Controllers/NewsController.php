<?php

namespace App\Controllers;

use App\Models\News;
use App\Models\Permissions;
use Core\Controller;

class NewsController extends Controller
{
    protected $newsModel;
    protected $permissionsModel;
    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->newsModel = new News();
        $this->permissionsModel = new Permissions();
    }

    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/news');

        $data = [
            'title' => 'News',
            'access' => $access
        ];

        view_with_layout('admin/news/index', $data);
    }

    public function data()
    {
        try {
            $news = $this->newsModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => asset('uploads/news/') . $item->image_name,
                    'content' => $item->content,
                    'publish' => $item->publish_date ? date('d M Y H:i', strtotime($item->publish_date)) : 'Draft',
                    'created_by' => $item->created_by,
                    'is_publish' => $item->is_publish
                ];
            }, $news);

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
    public function create()
    {
        view_with_layout('admin/news/form');
    }

    public function store()
    {
        try {
            $rawStatus = $_POST['status'] ?? 'draft';
            $isPublishBool = ($rawStatus === 'published');

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'News title is required']
                ],
                'content' => [
                    'required' => true,
                    'messages' => ['required' => 'News content is required']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum file size is 2MB',
                        'errors'  => [
                            'image' => 'Maximum file size is 2MB'
                        ]
                    ], 422);
                }

                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName    = $_FILES['image']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                $uploadFileDir = 'uploads/news/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $imageName = $newFileName;
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => [
                        'image' => 'Gambar wajib diupload'
                    ]
                ], 422);
            }

            $statusInput = $validation['data']['status'] ?? 'draft';
            $isPublish   = ($statusInput === 'published') ? 'true' : 'false';
            $publishDate = ($statusInput === 'published') ? date('Y-m-d H:i:s') : null;

            $data = [
                'title'        => $validation['data']['title'],
                'content'      => $validation['data']['content'],
                'image_name'   => $imageName,
                'is_publish'   => $isPublishBool ? 'true' : 'false',
                'publish_date' => $isPublishBool ? date('Y-m-d H:i:s') : null,
                'created_by'   => session('user')->id,
            ];

            $insertId = $this->newsModel->create($data);

            logActivity(
                "Create",
                "News '{$data['title']}' successfully created as " . strtoupper($statusInput),
                "news",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success',
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
            $news = $this->newsModel->findBy('id', $id);

            if (!$news) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'title' => 'Edit News',
                'data' => $news
            ];

            return view_with_layout('admin/news/form', $data);
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
            $oldData = $this->newsModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data news tidak ditemukan.'
                ], 404);
            }

            $rawStatus = $_POST['status'] ?? 'draft';
            $isPublishBool = ($rawStatus === 'published');

            $validation = validate([
                'title' => [
                    'required' => true,
                    'messages' => ['required' => 'News title is required']
                ],
                'content' => [
                    'required' => true,
                    'messages' => ['required' => 'News content is required']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $imageName = $oldData->image_name;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024;
                if ($_FILES['image']['size'] > $maxSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum file size is 2MB'
                    ], 422);
                }

                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName    = $_FILES['image']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = 'uploads/news/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {

                    if ($oldData->image_name && file_exists($uploadFileDir . $oldData->image_name)) {
                        unlink($uploadFileDir . $oldData->image_name);
                    }

                    $imageName = $newFileName;
                }
            }

            $newData = [
                'title'        => $validation['data']['title'],
                'content'      => $validation['data']['content'],
                'image_name'   => $imageName,
                'is_publish'   => $isPublishBool ? 'true' : 'false',
                'publish_date' => $isPublishBool ? date('Y-m-d H:i:s') : null,
            ];

            $this->newsModel->update($id, $newData);

            logActivity(
                "Update",
                "News '{$validation['data']['title']}' successfully updated",
                "news",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewNews($id)
    {
        try {
            $news = $this->newsModel->getNewsWithCreator($id);

            if (!$news) {
                redirect(base_url('admin/news'));
                exit;
            }

            $data = [
                'title' => 'View News',
                'news' => $news
            ];

            view_with_layout('admin/news/view', $data);
        } catch (\Exception $e) {
            redirect(base_url('admin/news'));
            exit;
        }
    }

    public function publish($id)
    {
        try {
            $oldData = $this->newsModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data news tidak ditemukan.'
                ], 404);
            }

            $newData = [
                'is_publish' => true,
                'publish_date' => date('Y-m-d H:i:s')
            ];

            $news = $this->newsModel->update($id, $newData);

            logActivity(
                "Update",
                "News $oldData->title successfully published",
                "news",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data news',
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
            $news = $this->newsModel->find($id);

            if (!$news) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data news tidak ditemukan.'
                ], 404);
            }

            if ($news->image_name && file_exists(asset('uploads/news/') . $news->image_name)) {
                unlink(asset('uploads/news/') . $news->image_name);
            }

            logActivity(
                "Delete",
                "News {$news->title} successfully deleted",
                "news",
                $id,
                $news,
                null
            );

            $this->newsModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data news',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
