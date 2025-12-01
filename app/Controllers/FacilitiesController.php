<?php

namespace App\Controllers;

use App\Models\Facilities;
use Core\Controller;

class FacilitiesController extends Controller
{
    protected $facilitiesModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->facilitiesModel = new Facilities();
    }

    public function index()
    {
        $data = [
            'title' => 'facilities',
        ];

        view_with_layout('admin/facilities/index', $data);
    }

    public function data()
    {
        try {
            $items = $this->facilitiesModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id'          => $item->id,
                    'name'        => $item->name,
                    'description' => $item->description,
                    'condition'   => $item->condition,
                    'qty'         => $item->qty,
                    'image'       => $item->image_name
                        ? asset('uploads/facilities/' . $item->image_name)
                        : asset('uploads/no-image.png'),
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
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view_with_layout('admin/facilities/form', [
            'title' => 'Create facilities'
        ]);
    }

    public function store()
    {
        try {
            // === VALIDASI ===
            $validation = validate([
                'name' => [
                    'required' => true,
                    'messages' => ['required' => 'Facility name is required']
                ],
                'description' => [
                    'required' => true,
                    'messages' => ['required' => 'Description is required']
                ],
                'condition' => [
                    'required' => true,
                    'messages' => ['required' => 'Condition is required']
                ],
                'qty' => [
                    'required' => true,
                    'messages' => ['required' => 'Quantity is required']
                ],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validation['errors']
                ], 422);
            }

            // === UPLOAD GAMBAR ===
            $imageName = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $maxSize = 2 * 1024 * 1024;

                if ($_FILES['image']['size'] > $maxSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum file size is 2MB',
                        'errors'  => ['image' => 'Maximum file size is 2MB']
                    ], 422);
                }

                $fileTmp = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $imageName = md5(time() . $fileName) . '.' . $ext;

                $uploadPath = 'uploads/facilities/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

                move_uploaded_file($fileTmp, $uploadPath . $imageName);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => ['image' => 'Image is required']
                ], 422);
            }

            // === SIMPAN DATA ===
            $data = [
                'name'        => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'condition'   => $validation['data']['condition'],
                'qty'         => $validation['data']['qty'],
                'image_name'  => $imageName
            ];

            var_dump($data);
            die;

            $id = $this->facilitiesModel->create($data);

            logActivity("Create", "Facility '{$data['name']}' created", "facilities", $id, null, $data);

            return response()->json([
                'success' => true,
                'message' => 'Facility successfully created',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $facility = $this->facilitiesModel->find($id);

            if (!$facility) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            return view_with_layout('admin/facilities/form', [
                'title' => 'Edit Facilities',
                'data'  => $facility
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    public function update($id)
    {
        try {
            $old = $this->facilitiesModel->find($id);

            if (!$old) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found'
                ], 404);
            }

            // === VALIDASI ===
            $validation = validate([
                'name' => ['required' => true],
                'description' => ['required' => true],
                'condition' => ['required' => true],
                'qty' => ['required' => true],
            ]);

            if (!$validation['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation['errors']
                ], 422);
            }

            $imageName = $old->image_name;

            // === Upload gambar baru (opsional) ===
            if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                $fileTmp = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFile = md5(time() . $fileName) . '.' . $ext;

                $uploadPath = 'uploads/facilities/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

                if (move_uploaded_file($fileTmp, $uploadPath . $newFile)) {
                    if ($old->image_name && file_exists($uploadPath . $old->image_name)) {
                        unlink($uploadPath . $old->image_name);
                    }
                    $imageName = $newFile;
                }
            }

            // === UPDATE DATA ===
            $data = [
                'name'        => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'condition'   => $validation['data']['condition'],
                'qty'         => $validation['data']['qty'],
                'image_name'  => $imageName
            ];

            $this->facilitiesModel->update($id, $data);

            logActivity("Update", "Facility '{$data['name']}' updated", "facilities", $id, $old, $data);

            return response()->json([
                'success' => true,
                'message' => 'Facility successfully updated',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->facilitiesModel->find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found'
                ], 404);
            }

            $path = 'uploads/facilities/';

            if ($data->image_name && file_exists($path . $data->image_name)) {
                unlink($path . $data->image_name);
            }

            $this->facilitiesModel->delete($id);

            logActivity("Delete", "Facility '{$data->name}' deleted", "facilities", $id, $data, null);

            return response()->json([
                'success' => true,
                'message' => 'Facility deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
