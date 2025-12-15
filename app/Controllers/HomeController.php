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

            $projectsRaw = $this->project->getLimit(6);

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
                        ? asset('uploads/gallery/images/') . $item->image_name
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
                $socials = is_string($rawSocial) ? json_decode($rawSocial, true) : $rawSocial;
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

            $rawPhone = $settings->phone ?? $settings->phone_number ?? '';
            $formattedPhone = format_phone_for_display($rawPhone);

            $data = [
                'site_name'    => $settings->site_name ?? 'InLET Lab',
                'email'        => $settings->email ?? '',
                'phone'        => $formattedPhone,

                'address'      => $settings->address ?? '',
                'map_src'      => $mapSrc,
                'logo'         => !empty($settings->site_logo) ? asset('uploads/settings/') . $settings->site_logo : asset('assets/logo/logo.png'),
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
            $query = $this->products->getLimit(2);

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

    public function detailTeam()
    {
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

            $formattedSocials = array_map(function ($social) {
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

    public function viewGallery()
    {
        return view_with_layout_homepage('home/gallery', [
            'title' => 'Laboratory Gallery'
        ]);
    }

    public function getGalleryContent()
    {
        try {
            $photosPage = isset($_GET['photos_page']) ? (int)$_GET['photos_page'] : 1;
            $videosPage = isset($_GET['videos_page']) ? (int)$_GET['videos_page'] : 1;

            // --- FOTO ---
            $perPagePhotos = 6;
            $totalPhotos = $this->gallery->countByType('Photo');
            $totalPagesPhotos = ceil($totalPhotos / $perPagePhotos);
            $offsetPhotos = ($photosPage - 1) * $perPagePhotos;
            $rawPhotos = $this->gallery->getByTypePaginated('Photo', $perPagePhotos, $offsetPhotos);

            $photos = array_map(function ($item) {
                return [
                    "id"          => $item->id,
                    "title"       => $item->title,
                    "description" => $item->description,
                    "src"         => !empty($item->image_name)
                        ? asset('uploads/gallery/images/') . $item->image_name
                        : 'https://placehold.co/600x800/png?text=No+Image',
                    "date"        => date('M Y', strtotime($item->upload_date))
                ];
            }, $rawPhotos);

            $photos_data = [
                'items' => $photos,
                'pagination' => [
                    'currentPage' => $photosPage,
                    'totalPages' => (int)$totalPagesPhotos,
                    'totalItems' => (int)$totalPhotos,
                    'perPage' => $perPagePhotos
                ]
            ];


            $perPageVideos = 3;
            $totalVideos = $this->gallery->countByType('Video');
            $totalPagesVideos = ceil($totalVideos / $perPageVideos);
            $offsetVideos = ($videosPage - 1) * $perPageVideos;
            $rawVideos = $this->gallery->getByTypePaginated('Video', $perPageVideos, $offsetVideos);

            $videos = array_map(function ($item) {
                return [
                    "id"          => $item->id,
                    "title"       => $item->title,
                    "description" => $item->description,
                    "thumbnail"   => !empty($item->image_name)
                        ? asset('uploads/gallery/images/') . $item->image_name
                        : 'https://placehold.co/800x450/000/fff?text=Video+Preview',
                    "video_url"   => $item->url ?? ''
                ];
            }, $rawVideos);

            $videos_data = [
                'items' => $videos,
                'pagination' => [
                    'currentPage' => $videosPage,
                    'totalPages' => (int)$totalPagesVideos,
                    'totalItems' => (int)$totalVideos,
                    'perPage' => $perPageVideos
                ]
            ];

            // 4. Return JSON
            return response()->json([
                'success' => true,
                'data' => [
                    'photos' => $photos_data,
                    'videos' => $videos_data
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching gallery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewNews()
    {
        return view_with_layout_homepage('home/news', [
            'title' => 'Latest News & Updates'
        ]);
    }

    public function viewNewsDetail($id)
    {
        $newsItem = $this->news->getNewsWithCreator($id);

        if (!$newsItem || !$newsItem->is_publish) {
            // Redirect or show 404 if not found or not published
            // For now, redirect to news list
            header('Location: /news');
            exit;
        }

        // Format Date
        $newsItem->formatted_date = date('F d, Y', strtotime($newsItem->publish_date));

        // Format Image
        $newsItem->image_url = !empty($newsItem->image_name)
            ? asset('uploads/news/') . $newsItem->image_name
            : 'https://placehold.co/1200x600/png?text=News+Image';

        return view_with_layout_homepage('home/news_detail', [
            'title' => $newsItem->title,
            'news' => $newsItem
        ]);
    }

    // --- API: FETCH NEWS LIST (PAGINATED) ---
    public function getNewsList()
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 6; // Adjust items per page

            // Get all published news
            $allNews = $this->news->getAll();

            // Filter only published
            $publishedNews = array_filter($allNews, function ($item) {
                return $item->is_publish == true;
            });

            // Sort by date DESC
            usort($publishedNews, function ($a, $b) {
                return strtotime($b->publish_date) - strtotime($a->publish_date);
            });

            // Pagination Logic
            $totalItems = count($publishedNews);
            $totalPages = ceil($totalItems / $perPage);
            $offset = ($page - 1) * $perPage;

            // Slice array for current page
            $paginatedItems = array_slice($publishedNews, $offset, $perPage);

            // Format Data for View
            $data = array_map(function ($item) {
                // Strip tags for preview
                $cleanContent = strip_tags($item->content);
                $preview = (strlen($cleanContent) > 150) ? substr($cleanContent, 0, 150) . '...' : $cleanContent;

                return [
                    "id"           => $item->id,
                    "title"        => $item->title,
                    "image_url"    => !empty($item->image_name)
                        ? asset('uploads/news/') . $item->image_name
                        : 'https://placehold.co/800x600/png?text=No+Image',
                    "date"         => date('M d, Y', strtotime($item->publish_date)),
                    "preview"      => $preview,
                    "author"       => $item->created_by ?? 'Admin'
                ];
            }, $paginatedItems);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $data,
                    'pagination' => [
                        'currentPage' => $page,
                        'totalPages' => $totalPages,
                        'totalItems' => $totalItems,
                        'hasPrev' => $page > 1,
                        'hasNext' => $page < $totalPages
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching news: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewProducts()
    {
        return view_with_layout_homepage('home/product', [
            'title' => 'Our Innovations'
        ]);
    }

    public function viewProductDetail($id)
    {
        return view_with_layout_homepage('home/product_detail', [
            'title' => 'Product Details'
        ]);
    }

    public function getProductListAPI()
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 5;

            // Ambil semua data produk
            $allProducts = $this->products->getAll();

            // Sort DESC by Release Date
            usort($allProducts, function ($a, $b) {
                return strtotime($b->release_date) - strtotime($a->release_date);
            });

            // Pagination Logic
            $totalItems = count($allProducts);
            $totalPages = ceil($totalItems / $perPage);
            $offset = ($page - 1) * $perPage;
            $paginatedItems = array_slice($allProducts, $offset, $perPage);

            $data = array_map(function ($item) {
                $cleanDesc = strip_tags($item->description);
                $shortDesc = (strlen($cleanDesc) > 180) ? substr($cleanDesc, 0, 180) . '...' : $cleanDesc;

                return [
                    "id"           => $item->id,
                    "name"         => $item->product_name, // Sesuai JSON DB
                    "image_url"    => !empty($item->image_name)
                        ? asset('uploads/product/') . $item->image_name
                        : 'https://placehold.co/800x600/f1f5f9/334155?text=Product+Showcase',
                    "date"         => date('F Y', strtotime($item->release_date)),
                    "description"  => $shortDesc,
                    "link"         => $item->product_link
                ];
            }, $paginatedItems);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $data,
                    'pagination' => [
                        'currentPage' => $page,
                        'totalPages' => $totalPages,
                        'hasPrev' => $page > 1,
                        'hasNext' => $page < $totalPages
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getProductDetailAPI($id)
    {
        try {
            // Asumsi model punya method find($id) atau query manual
            $product = $this->products->find($id);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            // Parsing JSON Strings dari Database
            $features = !empty($product->feature) ? json_decode($product->feature, true) : [];
            $specs = !empty($product->specification) ? json_decode($product->specification, true) : [];

            $data = [
                "id"           => $product->id,
                "name"         => $product->product_name,
                "description"  => $product->description,
                "image_url"    => !empty($product->image_name)
                    ? asset('uploads/product/') . $product->image_name
                    : 'https://placehold.co/1200x800/f1f5f9/334155?text=Product+Hero',
                "date"         => date('F d, Y', strtotime($product->release_date)),
                "features"     => $features, // Array hasil decode
                "specs"        => $specs,    // Array assoc hasil decode
                "product_link" => $product->product_link
            ];

            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function viewProjectDetail($slug)
    {
        $config = $this->siteSettings->getConfig('phone');
        $phone  = $config->phone ?? '';
        
        $phone = preg_replace('/[^0-9]/', '', $phone ?? '');

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return view_with_layout_homepage('home/project_detail', [
            'title' => 'Project Detail',
            'phone' => $phone
        ]);
    }

    public function getProjectDetailAPI($slug)
    {
        try {
            $allProjects = $this->project->getAll();
            $foundId = null;

            // Cari ID berdasarkan Slug (Looping)
            foreach ($allProjects as $p) {
                $pSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $p->name)));
                if ($pSlug === $slug) {
                    $foundId = $p->id;
                    break;
                }
            }

            if (!$foundId) {
                return response()->json(['success' => false, 'message' => 'Project not found'], 404);
            }

            // Ambil Detail Lengkap
            $project = $this->project->find($foundId);
            $details = $this->project->getProjectWithCategories($foundId);

            $data = [
                "id" => $project->id,
                "title" => $project->name,
                "description" => $project->description,
                "image_url" => !empty($details['images_list']) ? $details['images_list'][0] : 'https://placehold.co/1200x800/f8f9fa/adb5bd?text=Project',
                "categories" => $details['category_names'] ?? [],
                "gallery" => $details['images_list'] ?? [] // Jika ada multiple images
            ];

            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
