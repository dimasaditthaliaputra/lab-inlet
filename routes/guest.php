<?php

use App\Controllers\APIController;
use App\Controllers\HomeController;

return route_group([
    get('/', [HomeController::class, 'index']),
    get('/team', [HomeController::class, 'detailTeam']),
    get('/gallery', [HomeController::class, 'viewGallery']),
    get('/news', [HomeController::class, 'viewNews']),
    get('/news/{id}', [HomeController::class, 'viewNewsDetail']),
    get('/products', [HomeController::class, 'viewProducts']),
    get('/products/{id}', [HomeController::class, 'viewProductDetail']),
    get('/projects/{slug}', [HomeController::class, 'viewProjectDetail']),

    get('/api/schoolar', [APIController::class, 'index']),

    get('/api/team/{slug}', [HomeController::class, 'getTeamDetailAPI']),

    get('/api/hero-slider', [HomeController::class, 'getHeroSlider']),
    get('/api/about-us', [HomeController::class, 'getAbout']),
    get('/api/research', [HomeController::class, 'getResearchFocus']),
    get('/api/team', [HomeController::class, 'getTeam']),
    get('/api/facilities', [HomeController::class, 'getFacilities']),
    get('/api/projects', [HomeController::class, 'getProjects']),
    get('/api/news', [HomeController::class, 'getNews']),
    get('/api/partners', [HomeController::class, 'getPartners']),
    get('/api/gallery', [HomeController::class, 'getGallery']),
    get('/api/site-settings', [HomeController::class, 'getSiteSettings']),
    get('/api/products', [HomeController::class, 'getProducts']),
    get('/api/gallery-content', [HomeController::class, 'getGalleryContent']),
    get('/api/news-list', [HomeController::class, 'getNewsList']),
    get('/api/product-list', [HomeController::class, 'getProductListAPI']),
    get('/api/product-detail/{id}', [HomeController::class, 'getProductDetailAPI']),
    get('/api/project-detail/{slug}', [HomeController::class, 'getProjectDetailAPI']),
]);
