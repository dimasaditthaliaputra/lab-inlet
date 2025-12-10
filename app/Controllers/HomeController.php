<?php

namespace App\Controllers;

use App\Models\About;
use App\Models\Facilities;
use App\Models\Gallery;
use App\Models\HeroSlider;
use App\Models\KategoriProject;
use App\Models\News;
use App\Models\Partner;
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
        $this->gallery = new Gallery()
        ;$this->siteSettings = new SiteSettings();
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
            // Mengambil data dari Model Team (yang menggunakan STRING_AGG)
            $query = $this->team->getAll();

            $data = array_map(function ($item) {

                // Parsing Data Social Media
                // Format dari DB: "Instagram|http://link..., LinkedIn|http://link..."
                $socials = [];
                if (!empty($item->social_medias)) {
                    $pairs = explode(', ', $item->social_medias);
                    foreach ($pairs as $pair) {
                        $parts = explode('|', $pair);
                        // Pastikan formatnya benar (Name|Link)
                        if (count($parts) === 2) {
                            $socials[] = [
                                // strtolower agar cocok dengan class icon (misal: 'LinkedIn' -> 'linkedin')
                                'type' => strtolower($parts[0]),
                                'url'  => $parts[1]
                            ];
                        }
                    }
                }

                return [
                    "id"                => $item->id,
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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
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

            // 3. Susun Response sesuai format MOCK_API.projects
            // { categories: [...], items: [...] }
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
            // Ambil semua berita, urutkan dari yang terbaru
            // Model News::getAll() defaultnya urut ID, kita bisa modif query di model atau sort array disini.
            // Namun lebih efisien modif query di model untuk LIMIT.
            // Karena saya tidak boleh ubah model, saya akan slice array di sini.

            $allNews = $this->news->getAll();

            // Urutkan by publish_date DESC (Terbaru diatas)
            usort($allNews, function ($a, $b) {
                return strtotime($b->publish_date) - strtotime($a->publish_date);
            });

            // Ambil 4 berita terbaru
            $latestNews = array_slice($allNews, 0, 4);

            $data = array_map(function ($item) {
                // Generate excerpt (cuplikan konten) dari HTML content
                // Strip tags agar bersih dari tag HTML, lalu potong 100 karakter
                $cleanContent = strip_tags($item->content);
                $excerpt = (strlen($cleanContent) > 120) ? substr($cleanContent, 0, 120) . '...' : $cleanContent;

                // Generate slug sederhana (Title -> slug)
                // Sebaiknya DB punya kolom slug, tapi ini workaround
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
            // Ambil data site setting (asumsi ID 1)
            $settings = $this->siteSettings->getConfig();

            if (!$settings) {
                return response()->json(['success' => false, 'message' => 'Settings not found'], 404);
            }

            // Parsing Social Media JSON
            $socials = json_decode($settings->social_media_links, true) ?? [];

            // Bersihkan iframe dari HTML entity (jika tersimpan escaped) atau ambil raw
            // Kita perlu mengekstrak src dari iframe string jika frontend butuh src saja,
            // atau kirim iframe string utuh jika frontend inject HTML.
            // Sesuai JS snippet Anda, frontend tampaknya punya iframe hardcoded, 
            // jadi kita perlu ekstrak SRC dari $settings->embed_map_html 
            // ATAU kita kirim raw HTML map jika frontend mau replace innerHTML map-wrapper.
            
            // Opsi Terbaik: Ekstrak src map agar frontend bisa set attribute 'src' iframe yang sudah ada.
            $mapSrc = '';
            if (preg_match('/src="([^"]+)"/', $settings->embed_map_html, $match)) {
                $mapSrc = $match[1];
            } else {
                // Fallback dummy jika regex gagal
                $mapSrc = "https://maps.google.com/maps?q=Polinema&t=&z=13&ie=UTF8&iwloc=&output=embed";
            }

            $data = [
                'site_name'    => $settings->site_name,
                'email'        => $settings->email,
                'phone'        => $settings->phone_number,
                'address'      => $settings->address,
                'map_src'      => $mapSrc, // URL untuk iframe
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
}
