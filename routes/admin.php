<?php

use App\Controllers\AboutUsController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\RolesController;
use App\Controllers\TeamController;
use App\Controllers\UserController;

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

    //Team
    get('/team', [TeamController::class, 'index']),
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
    delete('/aboutus/{id}', [AboutUsController::class, 'destroy']),
]));
