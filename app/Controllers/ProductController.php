<?php

namespace App\Controllers;

use App\Models\Product;
use Core\Controller;

class ProductController extends Controller
{
    protected $productModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->productModel = new Product();
    }

    /* ================== INDEX & DATA ================== */

    public function index()
    {
        $data = [
            'title' => 'Product',
        ];

        view_with_layout('admin/product/index', $data);
    }

    public function data()
    {
        try {
            $products = $this->productModel->getAll();

            $data = array_map(function ($item) {
                $features = $item->feature ? json_decode($item->feature, true) : [];
                $specs    = $item->specification ? json_decode($item->specification, true) : [];

                return [
                    'id'            => $item->id,
                    'product_name'  => $item->product_name,
                    'image'         => $item->image_name ? asset('uploads/product/') . $item->image_name : null,
                    'description'   => $item->description,
                    'release_date'  => $item->release_date ? date('d M Y', strtotime($item->release_date)) : null,
                    'feature'       => $features ?: [],
                    'specification' => $specs ?: [],
                ];
            }, $products);

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

    /* ================== HELPER ================== */

    protected function uploadImage($oldImageName = null)
    {
        // tidak ada file di-upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
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
        $uploadFileDir = 'uploads/product/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
            if ($oldImageName && file_exists($uploadFileDir . $oldImageName)) {
                @unlink($uploadFileDir . $oldImageName);
            }
            return $newFileName;
        }

        throw new \Exception('Gagal memindahkan file upload.');
    }

    protected function buildFeaturesJson()
    {
        $features = $_POST['feature'] ?? [];
        $features = array_map('trim', (array)$features);
        $features = array_filter($features, function ($v) {
            return $v !== '';
        });

        if (empty($features)) {
            return null;
        }

        return json_encode(array_values($features), JSON_UNESCAPED_UNICODE);
    }

    protected function buildSpecificationJson()
    {
        $names  = $_POST['spec_name']  ?? [];
        $values = $_POST['spec_value'] ?? [];

        $specAssoc = [];

        foreach ((array)$names as $idx => $name) {
            $name  = trim($name);
            $value = isset($values[$idx]) ? trim($values[$idx]) : '';

            if ($name !== '' && $value !== '') {
                $specAssoc[$name] = $value;
            }
        }

        if (empty($specAssoc)) {
            return null;
        }

        return json_encode($specAssoc, JSON_UNESCAPED_UNICODE);
    }

    /* ================== CREATE & STORE ================== */

    public function create()
    {
        view_with_layout('admin/product/form');
    }

    public function store()
    {
        try {
            $validation = validate([
                'product_name' => [
                    'required' => true,
                    'messages' => ['required' => 'Product name is required']
                ],
                'release_date' => [
                    'required' => true,
                    'messages' => ['required' => 'Release date is required']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validation['errors']
                ], 422);
            }

            // image optional (kalau mau wajib tinggal cek di sini)
            try {
                $imageName = $this->uploadImage();
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => ['image' => $e->getMessage()]
                ], 422);
            }

            $featureJson = $this->buildFeaturesJson();
            $specJson    = $this->buildSpecificationJson();

            $data = [
                'product_name'  => $validation['data']['product_name'],
                'description'   => $_POST['description'] ?? null,
                'image_name'    => $imageName,
                'release_date'  => $validation['data']['release_date'],
                'feature'       => $featureJson,
                'specification' => $specJson,
            ];

            $insertId = $this->productModel->create($data);

            logActivity(
                "Create",
                "Product '{$data['product_name']}' successfully created",
                "product",
                $insertId,
                null,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Product berhasil ditambahkan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server, coba lagi.'
            ], 500);
        }
    }

    /* ================== EDIT & UPDATE ================== */

    public function edit($id)
    {
        try {
            $product = $this->productModel->findBy('id', $id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'title' => 'Edit Product',
                'data'  => $product
            ];

            return view_with_layout('admin/product/form', $data);
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
            $oldData = $this->productModel->find($id);

            if (!$oldData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data product tidak ditemukan.'
                ], 404);
            }

            $validation = validate([
                'product_name' => [
                    'required' => true,
                    'messages' => ['required' => 'Product name is required']
                ],
                'release_date' => [
                    'required' => true,
                    'messages' => ['required' => 'Release date is required']
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

            $featureJson = $this->buildFeaturesJson();
            $specJson    = $this->buildSpecificationJson();

            $newData = [
                'product_name'  => $validation['data']['product_name'],
                'description'   => $_POST['description'] ?? null,
                'image_name'    => $imageName,
                'release_date'  => $validation['data']['release_date'],
                'feature'       => $featureJson,
                'specification' => $specJson,
            ];

            $this->productModel->update($id, $newData);

            logActivity(
                "Update",
                "Product '{$validation['data']['product_name']}' successfully updated",
                "product",
                $id,
                $oldData,
                $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Product berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ================== DESTROY ================== */

    public function destroy($id)
    {
        try {
            $product = $this->productModel->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data product tidak ditemukan.'
                ], 404);
            }

            $uploadFileDir = 'uploads/product/';
            if ($product->image_name && file_exists($uploadFileDir . $product->image_name)) {
                @unlink($uploadFileDir . $product->image_name);
            }

            logActivity(
                "Delete",
                "Product {$product->product_name} successfully deleted",
                "product",
                $id,
                $product,
                null
            );

            $this->productModel->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Product berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
