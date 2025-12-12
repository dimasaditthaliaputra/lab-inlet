<?php

namespace App\Controllers;

use App\Models\About;
use App\Models\Facilities;
use App\Models\Gallery;
use App\Models\HeroSlider;
use App\Models\KategoriProject;
use App\Models\News;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProjectLab;
use App\Models\ResearchFocus;
use App\Models\SiteSettings;
use App\Models\Team;
use Core\Controller;

class HomeController extends Controller
{
    private $hero;
    private $about;
    private $team;
    private $research;
    private $facilities;
    private $project;
    private $category;
    private $news;
    private $partner;
    private $gallery;
    private $siteSettings;
    private $products;
    public function __construct()
    {
        $this->hero = new HeroSlider();
        $this->about = new About();
        $this->team = new Team();
        $this->research = new ResearchFocus();
        $this->facilities = new Facilities();
        $this->project = new ProjectLab();
        $this->category = new KategoriProject();
        $this->news = new News();
        $this->partner = new Partner();
        $this->gallery = new Gallery();
        $this->siteSettings = new SiteSettings();
        $this->products = new Product();
    }
    public function index()
    {
        $data = [
            'title' => 'Home Page',
        ];

        view_with_layout_homepage('home/index', $data);
    }

    public function getHeroSlider()
    {
        try {
            $query = $this->hero->getAll();

            $data = array_map(function ($item) {
                return [
                    "id" => $item->id,
                    "title" => $item->title,
                    "subtitle" => $item->subtitle,
                    "image_name" => asset('uploads/hero_slider/') . $item->image_name,
                    "button_text" => $item->button_text,
                    "button_url" => $item->button_url,
                    "sort_order" => $item->sort_order,
                    "is_active" => $item->is_active,
                    "created_at" => $item->created_at,
                ];
            }, $query);

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

    public function getAbout()
    {
        try {
            $query = $this->about->getAll();

            $data = [
                'id'          => $query[0]->id_about,
                'title'       => $query[0]->title,
                'description' => $query[0]->description,
                'vision'      => $query[0]->vision,
                'mission'      => $query[0]->mission,
                'aboutusimages'      => []
            ];

            foreach ($query as $row) {
                $data['aboutusimages'][] = [
                    'id'         => $row->id_image,
                    'aboutus_id' => $row->id_about,
                    'url' => asset('uploads/aboutus/') . $row->image_name,
                    'alt' => $row->image_name
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => [$data]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTeam()
    {
        try {
            $query = $this->team->getAll();

            $data = array_map(function ($item) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $item->full_name)));

                $socials = [];
                if (!empty($item->social_medias)) {
                    $pairs = explode(', ', $item->social_medias);
                    foreach ($pairs as $pair) {
                        $parts = explode('|', $pair);
                        if (count($parts) === 3) {
                            $socials[] = [
                                'type' => $parts[0],
                                'icon_name' => $parts[1],
                                'url'  => $parts[2]
                            ];
                        }
                    }
                }
                
                return [
                    "id"                => $item->id,
                    "slug"              => $slug, // <--- Field Baru
                    "full_name"         => $item->full_name,
                    "lab_position"      => $item->lab_position,
                    "academic_position" => $item->academic_position,
                    "image_name"        => asset('uploads/team/') . $item->image_name,
                    "social"            => $socials,
                    "created_at"        => $item->created_at,
                ];
            }, $query);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getResearchFocus()
    {
        try {
            $query = $this->research->getAll();

            $data = array_map(function ($item) {
                return [
                    "id" => $item->id,
                    "title" => $item->title,
                    "description" => $item->description,
                    "icon_name" => $item->icon_name
                ];
            }, $query);

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

    public function getFacilities()
    {
        try {
            $query = $this->facilities->getAll();

            $data = array_map(function ($item) {
                return [
                    "id"          => $item->id,
                    "name"        => $item->name,
                    "description" => $item->description,
                    "condition"   => $item->condition,
                    "qty"         => $item->qty,
                    "image_name"  => !empty($item->image_name)
                        ? asset('uploads/facilities/') . $item->image_name
                        : 'https://placehold.co/300x200/png?text=No+Image',
                ];
            }, $query);

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

    public function getProjects()
    {
        try {
            // 1. Ambil semua Kategori
            $categories = $this->category->getAll();
            $categoriesData = array_map(function ($cat) {
                return [
                    "id" => $cat->id,
                    "name" => $cat->name
                ];
            }, $categories);

            $projectsRaw = $this->project->getLimit();

            $itemsData = [];
            foreach ($projectsRaw as $p) {
                $projectCats = $this->project->getProjectWithCategories($p->id);

                $mainImage = 'https://placehold.co/600x400/png?text=No+Image';

                if (!empty($projectCats['images_list']) && count($projectCats['images_list']) > 0) {
                    $mainImage = $projectCats['images_list'][0]; // Ambil gambar pertama
                }

                $itemsData[] = [
                    "id" => $p->id,
                    "name" => $p->name,
                    "description" => $p->description,
                    "image_url" => $mainImage,
                    "category_ids" => $projectCats['category_ids'] ?? [] // Array ID Kategori [1, 2]
                ];
            }

            $responseData = [
                "categories" => $categoriesData,
                "items" => $itemsData
            ];

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getNews()
    {
        try {
            $allNews = $this->news->getAll();

            usort($allNews, function ($a, $b) {
                return strtotime($b->publish_date) - strtotime($a->publish_date);
            });

            $latestNews = array_slice($allNews, 0, 4);

            $data = array_map(function ($item) {
                $cleanContent = strip_tags($item->content);
                $excerpt = (strlen($cleanContent) > 120) ? substr($cleanContent, 0, 120) . '...' : $cleanContent;

                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $item->title)));

                return [
                    "id"           => $item->id,
                    "title"        => $item->title,
                    "image_name"   => !empty($item->image_name)
                        ? asset('uploads/news/') . $item->image_name
                        : 'https://placehold.co/600x400/png?text=News',
                    "publish_date" => $item->publish_date,
                    "excerpt"      => $excerpt,
                    "slug"         => $item->id // Gunakan ID untuk link detail (lebih aman daripada slug generate on-fly)
                ];
            }, $latestNews);

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

    public function getPartners()
    {
        try {
            $query = $this->partner->getAll();

            $data = array_map(function ($item) {
                return [
                    "id"           => $item->id,
                    "name"         => $item->partner_name,
                    "partner_logo" => !empty($item->partner_logo)
                        ? asset('uploads/partner_logo/') . $item->partner_logo
                        : 'https://placehold.co/150x50/png?text=' . urlencode($item->partner_name),
                    "url"          => $item->url
                ];
            }, $query);

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

    public function getGallery()
    {
        try {
            // Ambil data hanya yang bertipe 'Photo' sesuai request
            $query = $this->gallery->getByType('Photo');

            $data = array_map(function ($item) {
                return [
                    "id"          => $item->id,
                    "title"       => $item->title,
                    "description" => $item->description,
                    // Pastikan path sesuai folder upload
                    "image_name"  => !empty($item->image_name)
                        ? asset('assets/images/gallery/') . $item->image_name
                        : 'https://placehold.co/600x400/png?text=No+Image',
                    "type"        => $item->type,
                    "upload_date" => $item->upload_date
                ];
            }, $query);

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

    public function getSiteSettings()
    {
        try {
            // Ambil data site setting
            $settings = $this->siteSettings->getConfig();

            if (!$settings) {
                return response()->json(['success' => false, 'message' => 'Settings not found'], 404);
            }

            $rawSocial = $settings->social_media ?? $settings->social_media_links ?? null;
            $socials = [];

            if (!empty($rawSocial)) {
                // Tambahkan pengecekan tipe data sebelum decode
                $socials = is_string($rawSocial) ? json_decode($rawSocial, true) : $rawSocial;
                // Jika json_decode gagal (return null), set array kosong
                if (is_null($socials)) $socials = [];
            }

            $mapHtml = $settings->map_embed_url ?? '';
            $mapSrc = '';

            if (!empty($mapHtml) && is_string($mapHtml)) {
                if (preg_match('/<\s*iframe\s+[^>]*src="([^"]+)"/i', $mapHtml, $match)) {
                    $mapSrc = $match[1];
                } else {
                    $mapSrc = "https://maps.google.com/maps?q=Polinema&t=&z=13&ie=UTF8&iwloc=&output=embed";
                }
            } else {
                $mapSrc = "https://maps.google.com/maps?q=Polinema&t=&z=13&ie=UTF8&iwloc=&output=embed";
            }

            $data = [
                'site_name'    => $settings->site_name ?? 'InLET Lab',
                'email'        => $settings->email ?? '',
                'phone'        => $settings->phone ?? $settings->phone_number ?? '',

                'address'      => $settings->address ?? '',
                'map_src'      => $mapSrc,
                'logo'         => !empty($settings->site_logo) ? asset('uploads/site/') . $settings->site_logo : null,
                'social_media' => $socials
            ];

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProducts()
    {
        try {
            $query = $this->products->getAll();

            $data = array_map(function ($item) {
                return [
                    'id'          => $item->id,
                    'product_name'        => $item->product_name,
                    'description' => $item->description,
                    'image_name'  => !empty($item->image_name) ? asset('uploads/product/') . $item->image_name : null,
                    'release_date'       => $item->release_date,
                ];
            }, $query);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detailTeam() {
        return view_with_layout_homepage('home/detailTeam');
    }

    public function getTeamDetailAPI($slug)
    {
        try {
            $allMembers = $this->team->getAll();
            
            $foundId = null;
            
            foreach ($allMembers as $member) {
                $memberSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $member->full_name)));
                
                if ($memberSlug === $slug) {
                    $foundId = $member->id;
                    break;
                }
            }

            if (!$foundId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Team member not found'
                ], 404);
            }

            $data = $this->team->getTeamDetail($foundId);

            $data['image_name'] = !empty($data['image_name']) 
                                  ? asset('uploads/team/') . $data['image_name'] 
                                  : 'https://placehold.co/400x400/2563eb/ffffff?text=' . urlencode($data['full_name']);

            $formattedSocials = array_map(function($social) {
                return [
                    'type' => strtolower($social->name),
                    'icon_name' => $social->icon_name,
                    'url' => $social->link_sosmed
                ];
            }, $data['social_medias']);

            $data['social_medias'] = $formattedSocials;

            // Decode JSON fields
            $jsonFields = ['expertise', 'education', 'certifications', 'courses_taught'];
            foreach ($jsonFields as $field) {
                if (isset($data[$field]) && is_string($data[$field])) {
                    $data[$field] = json_decode($data[$field], true);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
