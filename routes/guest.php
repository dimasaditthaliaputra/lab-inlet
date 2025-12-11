<?php

use App\Controllers\APIController;
use App\Controllers\HomeController;

return route_group([
    get('/', [HomeController::class, 'index']),
    get('/api/schoolar', [APIController::class, 'index']),

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
]);