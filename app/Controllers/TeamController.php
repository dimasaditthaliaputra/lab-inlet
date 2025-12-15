<?php

namespace App\Controllers;

use App\Models\Permissions;
use App\Models\SocialLinks;
use App\Models\Team;
use Core\Controller;

class TeamController extends Controller
{
    protected $teamModel;
    protected $permissionsModel;
    protected $socialModel;

    public function __construct()
    {
        if (!attempt_auto_login()) {
            redirect(base_url('login'));
            exit;
        }

        $this->teamModel = new Team();
        $this->permissionsModel = new Permissions();
        $this->socialModel = new SocialLinks();
    }


    public function index()
    {
        $user = session('user');
        $roleId = $user->id_roles ?? 0;

        $access = $this->permissionsModel->getPermissionByRoute($roleId, 'admin/team');

        $data = [
            'title' => 'Team Member',
            'socialMedias' => $this->socialModel->getAll(),
            'access' => $access
        ];

        view_with_layout('admin/team/index', $data);
    }

    public function data()
    {
        try {
            $teams = $this->teamModel->getAll();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $teams
            ]);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function getSocialMedia()
    {
        try {
            $socialMedias = $this->socialModel->getAll();

            return response()->json([
                'success' => true,
                'data' => $socialMedias
            ]);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function create()
    {
        return view_with_layout('admin/team/form', [
            'title' => 'Add Team',
            'socialMediaOptions' => $this->socialModel->getAll()
        ]);
    }

    public function store()
    {
        try {
            $imageName = $this->uploadImage('image', 'uploads/team/');

            $insertData = $this->prepareTeamData();
            $insertData['image_name'] = $imageName;

            $teamId = $this->teamModel->create($insertData);

            $socialData = $this->prepareSocialMediaData();
            $this->teamModel->assignSocialMedias($teamId, $socialData);

            return response()->json(['success' => true, 'message' => 'Team added successfully']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }


    public function edit($id)
    {
        $team = $this->teamModel->getTeamDetail($id);
        if (!$team) return redirect(base_url('admin/team'));

        return view_with_layout('admin/team/form', [
            'title' => 'Edit Team',
            'team'  => $team,
            'socialMediaOptions' => $this->socialModel->getAll()
        ]);
    }

    public function update($id)
    {
        try {
            $team = $this->teamModel->getTeamDetail($id);
            if (!$team) return response()->json(['success' => false], 404);

            $imageName = $this->uploadImage('image', 'uploads/team/', $team['image_name']);

            $updateData = $this->prepareTeamData();
            $updateData['image_name'] = $imageName;

            $this->teamModel->update($id, $updateData);

            $socialData = $this->prepareSocialMediaData();
            $this->teamModel->assignSocialMedias($id, $socialData);

            return response()->json(['success' => true, 'message' => 'Team updated']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function show($id)
    {
        try {
            $team = $this->teamModel->getTeamDetail($id);

            if (!$team) {
                redirect(base_url('admin/team'));
                exit;
            }

            $decode = function ($json) {
                if (empty($json)) return [];
                if (is_array($json)) return $json;
                $decoded = json_decode($json, true);
                return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            };

            $expertises     = $decode($team['expertise'] ?? null);
            $educations     = $decode($team['education'] ?? null);
            $certifications = $decode($team['certifications'] ?? null);
            $courses        = $decode($team['courses_taught'] ?? null);

            $defaultEdu = ['university' => '', 'major' => ''];
            $team['education'] = [
                'S1' => $educations['S1'] ?? $defaultEdu,
                'S2' => $educations['S2'] ?? $defaultEdu,
                'S3' => $educations['S3'] ?? $defaultEdu,
            ];

            $team['courses_taught'] = [
                'ganjil' => $courses['ganjil'] ?? [],
                'genap'  => $courses['genap']  ?? []
            ];

            $team['certifications'] = is_array($certifications) ? $certifications : [];
            $team['expertise']      = is_array($expertises) ? $expertises : [];

            if (!isset($team['social_medias']) || !is_array($team['social_medias'])) {
                $team['social_medias'] = [];
            }

            $data = [
                'title' => 'View Team',
                'team' => $team
            ];

            view_with_layout('admin/team/view', $data);
        } catch (\Exception $e) {
            redirect(base_url('admin/team'));
            exit;
        }
    }

    public function destroy($id)
    {
        try {
            $team = $this->teamModel->find($id);
            if (!$team) return response()->json(['success' => false], 404);

            if ($team->image_name && file_exists('uploads/team/' . $team->image_name)) {
                unlink('uploads/team/' . $team->image_name);
            }

            $this->teamModel->delete($id);

            return response()->json(['success' => true, 'message' => 'Team deleted']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    private function uploadImage($input, $dir, $old = null)
    {
        if (!isset($_FILES[$input]) || $_FILES[$input]['error'] === UPLOAD_ERR_NO_FILE) {
            return $old;
        }

        $file = $_FILES[$input];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = md5(time() . $file['name']) . '.' . $ext;

        if (!is_dir($dir)) mkdir($dir, 0777, true);
        move_uploaded_file($file['tmp_name'], $dir . $newName);

        if ($old && file_exists($dir . $old)) unlink($dir . $old);

        return $newName;
    }


    private function prepareTeamData()
    {
        $expertise = array_values(array_filter(request('expertise') ?? []));

        $education = [
            'S1' => [
                'university' => request('education_s1_univ'),
                'major'      => request('education_s1_major')
            ],
            'S2' => [
                'university' => request('education_s2_univ'),
                'major'      => request('education_s2_major')
            ],
            'S3' => [
                'university' => request('education_s3_univ'),
                'major'      => request('education_s3_major')
            ]
        ];

        $certNames = request('cert_name') ?? [];
        $certPubs  = request('cert_publisher') ?? [];
        $certYears = request('cert_year') ?? [];

        $certifications = [];
        foreach ($certNames as $index => $name) {
            if (!empty($name)) {
                $certifications[] = [
                    'name'      => $name,
                    'publisher' => $certPubs[$index] ?? '',
                    'year'      => $certYears[$index] ?? ''
                ];
            }
        }

        $coursesTaught = [
            'ganjil' => array_values(array_filter(request('courses_ganjil') ?? [])),
            'genap'  => array_values(array_filter(request('courses_genap') ?? []))
        ];

        return [
            'full_name'         => request('full_name'),
            'nip'               => request('nip'),
            'nidn'              => request('nidn'),
            'lab_position'      => request('lab_position'),
            'academic_position' => request('academic_position'),
            'study_program'     => request('study_program'),
            'email'             => request('email'),
            'office_address'    => request('office_address'),
            'expertise'         => json_encode($expertise),
            'education'         => json_encode($education),
            'certifications'    => json_encode($certifications),
            'courses_taught'    => json_encode($coursesTaught),
        ];
    }

    private function prepareSocialMediaData()
    {
        $socialMediaIds   = request('social_media_id') ?? [];
        $socialMediaLinks = request('social_media_link') ?? [];

        $socialData = [];
        foreach ($socialMediaIds as $i => $id) {
            if (!empty($id) && !empty($socialMediaLinks[$i])) {
                $socialData[] = [
                    'id_social_media' => $id,
                    'link_sosmed'     => $socialMediaLinks[$i]
                ];
            }
        }
        return $socialData;
    }

    private function serverError($e)
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage() . ' Line: ' . $e->getLine()
        ], 500);
    }
}
