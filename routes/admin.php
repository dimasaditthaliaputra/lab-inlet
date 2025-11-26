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
]));
