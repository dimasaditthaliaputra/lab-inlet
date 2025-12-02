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
                    'image_name' => !empty($item->image_name) ? asset('uploads/facilities/') . $item->image_name : null,
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
        view_with_layout('admin/facilities/form');
    }

    public function store()
    {
        try {
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

            $imageName = null;

            if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image_name'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $imageName = md5(time() . $file['name']) . '.' . $ext;
                $dir = 'uploads/facilities/';

                if (!is_dir($dir)) mkdir($dir, 0777, true);
                move_uploaded_file($file['tmp_name'], $dir . $imageName);
            }

            $data = [
                'name'        => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'condition'   => $validation['data']['condition'],
                'qty'         => $validation['data']['qty'],
                'image_name'  => $imageName
            ];

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
            $facilities = $this->facilitiesModel->findBy('id', $id);

            if (!$facilities) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data = [
                'title' => 'Edit Facilities',
                'data' => $facilities
            ];

            return view_with_layout('admin/facilities/form', $data);
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
                    'message' => 'Validasi gagal',
                    'errors' => $validation['errors']
                ], 422);
            }

            $facilities = $this->facilitiesModel->find($id);
            if (!$facilities) return response()->json(['success' => false], 404);

            $imageName = $facilities->image_name;
            if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image_name'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newImage = md5(time() . $file['name']) . '.' . $ext;
                $dir = 'uploads/facilities/';

                if (!is_dir($dir)) mkdir($dir, 0777, true);
                move_uploaded_file($file['tmp_name'], $dir . $newImage);

                if ($facilities->image_name && file_exists($dir . $facilities->image_name)) {
                    @unlink($dir . $facilities->image_name);
                }

                $imageName = $newImage;
            }

            $data = [
                'name'        => $validation['data']['name'],
                'description' => $validation['data']['description'],
                'condition'   => $validation['data']['condition'],
                'qty'         => $validation['data']['qty'],
                'image_name'  => $imageName
            ];

            $this->facilitiesModel->update($id, $data);

            logActivity("Update", "Facility '{$data['name']}' updated", "facilities", $id, $data);

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
