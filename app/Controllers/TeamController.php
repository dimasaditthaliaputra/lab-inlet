<?php

namespace App\Controllers;

use App\Models\Team;
use Core\Controller;

class TeamController extends Controller
{
    protected $teamModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('admin/login'));
            exit;
        }

        $this->teamModel = new Team();
    }

    public function index()
    {
        $data = ['title' => 'Team'];
        view_with_layout('admin/team/index', $data);
    }

    public function data()
    {
        try {
            $teams = $this->teamModel->getAll();

            $data = array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->full_name,
                    'nip' => $item->nip,
                    'nidn' => $item->nidn,
                    'lab_position' => $item->lab_position,
                    'academic_position' => $item->academic_position,
                    'study_program' => $item->study_program,
                    'email' => $item->email,
                    'office_address' => $item->office_address,
                    'image_name' => !empty($item->image_name) ? asset('uploads/team/') . $item->image_name : null,
                    'expertise' => $item->expertise,
                    'education' => $item->education,
                    'certifications' => $item->certifications,
                    'courses_taught' => $item->courses_taught,
                    'social_links' => $item->social_links,
                ];
            }, $teams);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return view_with_layout('admin/team/form', ['title' => 'Add Team']);
    }

    public function store()
    {
        try {
            $imageName = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $imageName = md5(time() . $file['name']) . '.' . $ext;
                $dir = 'uploads/team/';

                if (!is_dir($dir)) mkdir($dir, 0777, true);
                move_uploaded_file($file['tmp_name'], $dir . $imageName);
            }

            $certNames = request('cert_name') ?? [];
            $certPublishers = request('cert_publisher') ?? [];
            $certifications = [];
            for ($i = 0; $i < count($certNames); $i++) {
                $name = trim($certNames[$i]);
                if ($name === '') continue;
                $certifications[] = [
                    'name' => $name,
                    'publisher' => isset($certPublishers[$i]) ? trim($certPublishers[$i]) : ''
                ];
            }

            $data = [
                'full_name'         => request('full_name'),
                'nip'               => request('nip'),
                'nidn'              => request('nidn'),
                'lab_position'      => request('lab_position'),
                'academic_position' => request('academic_position'),
                'study_program'     => request('study_program'),
                'email'             => request('email'),
                'office_address'    => request('office_address'),
                'image_name'        => $imageName,
                'expertise' => json_encode(array_values(array_filter(request('expertise') ?? []))),
                'education' => json_encode([
                    'S1' => [
                        'university' => request('education_s1_university'),
                        'major'      => request('education_s1_major'),
                    ],
                    'S2' => [
                        'university' => request('education_s2_university'),
                        'major'      => request('education_s2_major'),
                    ],
                    'S3' => [
                        'university' => request('education_s3_university'),
                        'major'      => request('education_s3_major'),
                    ],
                ]),
                'certifications' => json_encode($certifications),
                'courses_taught' => json_encode([
                    'ganjil' => array_values(array_filter(request('courses_ganjil') ?? [])),
                    'genap'  => array_values(array_filter(request('courses_genap') ?? [])),
                ]),
                'social_links' => json_encode([
                    'linkedin'       => request('linkedin'),
                    'google_scholar' => request('google_scholar'),
                    'sinta'          => request('sinta'),
                    'email_contact'  => request('social_email'),
                    'cv'             => request('cv'),
                ]),
            ];


            $insertId = $this->teamModel->create($data);

            logActivity("Create", "Create Team {$data['full_name']}", "team", $insertId, null, $data);

            return response()->json(['success' => true, 'message' => 'Team added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $team = $this->teamModel->find($id);

        if (!$team) return redirect(base_url('admin/team'));

        return view_with_layout('admin/team/form', [
            'title' => 'Edit Team',
            'team'  => $team
        ]);
    }

    public function update($id)
    {
        try {
            $team = $this->teamModel->find($id);
            if (!$team) return response()->json(['success' => false], 404);

            $imageName = $team->image_name;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newImage = md5(time() . $file['name']) . '.' . $ext;
                $dir = 'uploads/team/';

                if (!is_dir($dir)) mkdir($dir, 0777, true);
                move_uploaded_file($file['tmp_name'], $dir . $newImage);

                if ($team->image_name && file_exists($dir . $team->image_name)) {
                    @unlink($dir . $team->image_name);
                }

                $imageName = $newImage;
            }

            $certNames = request('cert_name') ?? [];
            $certPublishers = request('cert_publisher') ?? [];
            $certifications = [];
            for ($i = 0; $i < count($certNames); $i++) {
                $name = trim($certNames[$i]);
                if ($name === '') continue;
                $certifications[] = [
                    'name' => $name,
                    'publisher' => isset($certPublishers[$i]) ? trim($certPublishers[$i]) : ''
                ];
            }

            $newData = [
                'full_name'         => request('full_name'),
                'nip'               => request('nip'),
                'nidn'              => request('nidn'),
                'lab_position'      => request('lab_position'),
                'academic_position' => request('academic_position'),
                'study_program'     => request('study_program'),
                'email'             => request('email'),
                'office_address'    => request('office_address'),
                'image_name'        => $imageName,
                'expertise' => json_encode(array_values(array_filter(request('expertise') ?? []))),
                'education' => json_encode([
                    'S1' => [
                        'university' => request('education_s1_university'),
                        'major'      => request('education_s1_major'),
                    ],
                    'S2' => [
                        'university' => request('education_s2_university'),
                        'major'      => request('education_s2_major'),
                    ],
                    'S3' => [
                        'university' => request('education_s3_university'),
                        'major'      => request('education_s3_major'),
                    ],
                ]),
                'certifications' => json_encode($certifications),
                'courses_taught' => json_encode([
                    'ganjil' => array_values(array_filter(request('courses_ganjil') ?? [])),
                    'genap'  => array_values(array_filter(request('courses_genap') ?? [])),
                ]),
                'social_links' => json_encode([
                    'linkedin'       => request('linkedin'),
                    'google_scholar' => request('google_scholar'),
                    'sinta'          => request('sinta'),
                    'email_contact'  => request('social_email'),
                    'cv'             => request('cv'),
                ]),
            ];


            $this->teamModel->update($id, $newData);

            logActivity("Update", "Update Team {$team->full_name}", "team", $id, $team, $newData);

            return response()->json(['success' => true, 'message' => 'Team updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $team = $this->teamModel->find($id);
            if (!$team) return response()->json(['success' => false], 404);

            $dir = "uploads/team/";
            if ($team->image_name && file_exists($dir . $team->image_name)) {
                @unlink($dir . $team->image_name);
            }

            $this->teamModel->delete($id);

            logActivity("Delete", "Delete Team {$team->full_name}", "team", $id, $team);

            return response()->json(['success' => true, 'message' => 'Team deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
