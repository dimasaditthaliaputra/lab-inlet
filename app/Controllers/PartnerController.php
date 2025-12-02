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
                    'partner_logo' => asset('uploads/partner_logo/') . $item->partner_logo,
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
            $validation = validate([
                'partner_name' => [
                    'required' => true,
                    'messages' => ['required' => 'Partner name is required']
                ],
                'url' => [
                    'required' => false
                ],
                'partner_logo' => [
                    'required' => false
                ]
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $imageName = null;
            if (isset($_FILES['partner_logo']) && $_FILES['partner_logo']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024;
                if ($_FILES['partner_logo']['size'] > $maxSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ukuran gambar tidak boleh lebih dari 2MB',
                        'errors'  => [
                            'image' => 'Maximum file size is 2MB'
                        ]
                    ], 422);
                }

                $fileTmpPath = $_FILES['partner_logo']['tmp_name'];
                $fileName    = $_FILES['partner_logo']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                $uploadFileDir = 'uploads/partner_logo/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $imageName = $newFileName;
                }
            }

            $data = [
                'partner_name' => $validation['data']['partner_name'],
                'url' => $validation['data']['url'],
                'partner_logo' => $imageName
            ];

            $insertId = $this->partnerModel->create($data);

            logActivity(
                "Create",
                "Partner '{$data['partner_name']}' successfully created",
                "partner",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success menambahkan partner baru',
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

            $data = [
                'id' => $partner->id,
                'partner_name' => $partner->partner_name,
                'partner_logo' => asset('uploads/partner_logo/') . $partner->partner_logo,
                'url' => $partner->url,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $data
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
                    'message' => 'Data partner tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'partner_name' => [
                    'required' => true,
                    'messages' => ['required' => 'Partner name is required']
                ],
                'url' => [
                    'required' => false
                ],
                'partner_logo' => [
                    'required' => false
                ]
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $imageName = null;
            if (isset($_FILES['partner_logo']) && $_FILES['partner_logo']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024;
                if ($_FILES['partner_logo']['size'] > $maxSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ukuran gambar tidak boleh lebih dari 2MB',
                        'errors'  => [
                            'image' => 'Maximum file size is 2MB'
                        ]
                    ], 422);
                }

                $fileTmpPath = $_FILES['partner_logo']['tmp_name'];
                $fileName    = $_FILES['partner_logo']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                $uploadFileDir = 'uploads/partner_logo/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $imageName = $newFileName;

                    if (!empty($partner->partner_logo)) {
                        $oldFilePath = __DIR__ . '/../../public/uploads/partner_logo/' . $partner->partner_logo;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                }
            }

            $data = [
                'partner_name' => $validation['data']['partner_name'],
                'url' => $validation['data']['url'],
            ];

            if ($imageName) {
                $data['partner_logo'] = $imageName;
            }

            $this->partnerModel->update($id, $data);

            logActivity(
                "Update",
                "Partner '{$data['partner_name']}' successfully updated",
                "partner",
                $id,
                $partner,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Success memperbarui data partner',
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
                    'message' => 'Data partner tidak ditemukan.'
                ], 404);
            }

            if (!empty($partner->partner_logo)) {
                $path = __DIR__ . '/../../public/uploads/partner_logo/' . $partner->partner_logo;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            logActivity(
                "Delete",
                "Partner {$partner->partner_logo} successfully deleted",
                "partner",
                $id,
                $partner,
                null
            );

            $this->partnerModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Success menghapus data partner',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
