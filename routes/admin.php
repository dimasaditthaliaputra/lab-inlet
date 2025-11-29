<?php

use App\Controllers\AboutUsController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\LogActivityController;
use App\Controllers\NewsController;
use App\Controllers\RolesController;
use App\Controllers\TeamController;
use App\Controllers\UserController;
use App\Controllers\PartnerController;
use App\Controllers\UserRedirectController;
use App\Controllers\KategoriProjectController;
use App\Controllers\ProjectLabController;
use App\Controllers\GalleryController;


return prefix('admin', route_group([

    // Login
    get('/login', [AuthController::class, 'showLoginForm']),
    post('/login/process', [AuthController::class, 'login']),
    post('/logout', [AuthController::class, 'logout']),

    // Dashboard
    get('/dashboard', [DashboardController::class, 'index']),

    // Roles
    get('/roles', [RolesController::class, 'index']),
    get('/roles/data', [RolesController::class, 'data']),
    post('/roles', [RolesController::class, 'store']),
    get('/roles/{id}/edit', [RolesController::class, 'edit']),
    put('/roles/{id}', [RolesController::class, 'update']),
    delete('/roles/{id}', [RolesController::class, 'destroy']),

    get('/user', [UserController::class, 'index']),
    get('/user/data', [UserController::class, 'data']),
    post('/user', [UserController::class, 'store']),
    get('/user/{id}/edit', [UserController::class, 'edit']),
    put('/user/{id}', [UserController::class, 'update']),
    delete('/user/{id}', [UserController::class, 'destroy']),
    get('/profile/{id}', [UserController::class, 'profile']),
    put('/profile/{id}', [UserController::class, 'updateProfile']),

    //Team
    get('/team', [TeamController::class, 'index']),
    get('/team/create', [TeamController::class, 'create']),
    get('/team/data', [TeamController::class, 'data']),
    post('/team', [TeamController::class, 'store']),
    get('/team/{id}/edit', [TeamController::class, 'edit']),
    put('/team/{id}', [TeamController::class, 'update']),
    delete('/team/{id}', [TeamController::class, 'destroy']),

    //About Us
    get('/aboutus', [AboutUsController::class, 'index']),
    get('/aboutus/data', [AboutUsController::class, 'data']),
    post('/aboutus', [AboutUsController::class, 'store']),
    get('/aboutus/{id}/edit', [AboutUsController::class, 'edit']),
    put('/aboutus/{id}', [AboutUsController::class, 'update']),

    //Partner
    get('/partner', [PartnerController::class, 'index']),
    get('/partner/data', [PartnerController::class, 'data']),
    post('/partner', [PartnerController::class, 'store']),
    get('/partner/{id}/edit', [PartnerController::class, 'edit']),
    put('/partner/{id}', [PartnerController::class, 'update']),
    delete('/partner/{id}', [PartnerController::class, 'destroy']),

    get('/news', [NewsController::class, 'index']),
    get('/news/data', [NewsController::class, 'data']),
    get('/news/create', [NewsController::class, 'create']),
    post('/news/store', [NewsController::class, 'store']),
    get('/news/{id}/edit', [NewsController::class, 'edit']),
    post('/news/{id}', [NewsController::class, 'update']),
    put('/news/publish/{id}', [NewsController::class, 'publish']),
    delete('/news/{id}', [NewsController::class, 'destroy']),

    // Kategori Project
    get('/kategori-project', [KategoriProjectController::class, 'index']),
    get('/kategori-project/data', [KategoriProjectController::class, 'data']),
    post('/kategori-project', [KategoriProjectController::class, 'store']),
    get('/kategori-project/{id}/edit', [KategoriProjectController::class, 'edit']),
    put('/kategori-project/{id}', [KategoriProjectController::class, 'update']),
    delete('/kategori-project/{id}', [KategoriProjectController::class, 'destroy']),

    // Project Lab
    get('/project-lab', [ProjectLabController::class, 'index']),
    get('/project-lab/data', [ProjectLabController::class, 'data']),
    post('/project-lab', [ProjectLabController::class, 'store']),
    get('/project-lab/{id}/edit', [ProjectLabController::class, 'edit']),
    put('/project-lab/{id}', [ProjectLabController::class, 'update']),
    delete('/project-lab/{id}', [ProjectLabController::class, 'destroy']),

    // Log Activity
    get('/log-activity', [LogActivityController::class, 'index']),
    get('/log-activity/data', [LogActivityController::class, 'data']),

    // Route User Redirect
    get('/userRedirect', [UserRedirectController::class, 'index']),
    get('/userRedirect/create', [UserRedirectController::class, 'create']),
    post('/userRedirect/insert', [UserRedirectController::class, 'store']),
    get('/userRedirect/{id}/edit', [UserRedirectController::class, 'edit']),
    put('/userRedirect/{id}/update', [UserRedirectController::class, 'update']),
    delete('/userRedirect/{id}/delete', [UserRedirectController::class, 'destroy']),



        // Gallery Image
    get('/gallery/image', [GalleryController::class, 'imageIndex']),
    get('/gallery/image/data', [GalleryController::class, 'imageData']),
    post('/gallery/image', [GalleryController::class, 'imageStore']),
    get('/gallery/image/{id}/edit', [GalleryController::class, 'imageEdit']),
    put('/gallery/image/{id}', [GalleryController::class, 'imageUpdate']),
    delete('/gallery/image/{id}', [GalleryController::class, 'imageDestroy']),

    // Gallery Video
    get('/gallery/video', [GalleryController::class, 'videoIndex']),
    get('/gallery/video/data', [GalleryController::class, 'videoData']),
    post('/gallery/video', [GalleryController::class, 'videoStore']),
    get('/gallery/video/{id}/edit', [GalleryController::class, 'videoEdit']),
    put('/gallery/video/{id}', [GalleryController::class, 'videoUpdate']),
    delete('/gallery/video/{id}', [GalleryController::class, 'videoDestroy']),

]));